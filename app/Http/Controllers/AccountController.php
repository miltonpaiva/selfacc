<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\SimpleValues as SV;
use App\Models\Search;
use App\Models\Music;
use App\Models\MusicQueue;

class AccountController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $auth_data = self::getAuthData();
        try {
            $playing = (new Music())::getPlayingStatus();
        } catch (\Throwable $th) {
            $playing = [];
        }

        try {
            $queue = (new Music())::getQueue();
        } catch (\Throwable $th) {
            $queue = [];
        }

        $customer_queue = MusicQueue::getQueue();

        $queue['queue'] = array_merge($customer_queue, $queue['queue'] ?? []);

        $data =
        [
            'products'   => convertFieldsMapToFormList(Product::all()->toArray(), new Product()),
            'categories' => SV::list('category_pd', true),
            'auth_data'  => $auth_data,
            'orders'     => $auth_data? Order::getActivesByTableNumber($auth_data['account']['table_number']) : null ,
            'playing'    => $playing,
            'queue'      => $queue,
        ];

        return view('index', $data);
    }

    public function createAccount(Request $request): object
    {
        $customer_validated_data = runModelValidates($request, new Customer());
        if(is_object($customer_validated_data)) return $customer_validated_data;

        $customer_data = convertFieldsMapToModel($customer_validated_data, new Customer());

        try {
            $customer = Customer::create($customer_data);
        } catch (\Throwable $th) {
            return self::error('Não foi possivel criar: ' . $th->getMessage(), $customer_data, 500);
        }

        $initial_status_id = SV::getValueId('status_ac', 'Aberta');
        $request->merge(['customer_id' => $customer->getKey()]);
        $request->merge(['status_id'   => $initial_status_id]);

        // realizando a indexação do cliente para busca
        (new Search($customer, ''))::runIndexes($customer->toArray());

        $account_validated_data = runModelValidates($request, new Account());
        if(is_object($account_validated_data)) return $account_validated_data;

        $account_data = convertFieldsMapToModel($account_validated_data, new Account());

        try {
            $account = Account::create($account_data);
        } catch (\Throwable $th) {
            return self::error('Não foi possivel criar: ' . $th->getMessage(), $account_data, 500);
        }

        $results =
        [
            'customer' => convertFieldsMapToForm($customer->toArray(), $customer),
            'account'  => convertFieldsMapToForm($account->toArray(), $account),
        ];

        // registrando a sessão do usuario
        self::setAuthData('customer', $results['customer']);
        self::setAuthData('account',  $results['account']);

        $results['orders'] = Order::getActivesByTableNumber($results['account']['table_number']);

        return self::success("Criado !", $results);
    }

    public function indexAdmin()
    {
        $orders = convertFieldsMapToFormList(Order::all()->toArray(), new Order());

        $grouped = [];
        foreach ($orders as $key => $order) {
            $grouped[$order['table_number']][] = $order;
        }

        $data =
        [
            'products'   => convertFieldsMapToFormList(Product::all()->toArray(), new Product()),
            'categories' => SV::list('category_pd', true),
            'orders'     => $grouped,
        ];

        return view('index_admin', $data);
    }
}
