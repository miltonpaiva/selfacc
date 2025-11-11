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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

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

        $customer_queue = MusicQueue::getQueue()['queue'] ?? [];

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

        if($request->get('is_admin')){
            $tables = Account::getActives();
            return self::success("Criado !", ['tables' => $tables]);
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

    /**
     * indexAdmin - Exibe a visão administrativa com todas as mesas ativas.
     *
     * @return Illuminate\Contracts\View\View
     */
    public function indexAdmin(): \Illuminate\Contracts\View\View
    {
        $tables = Account::getActives();

        $data =
        [
            'products'          => convertFieldsMapToFormList(Product::all()->toArray(), new Product()),
            'products_agrouped' => Product::getProductsAgrouped(),
            'tables'            => $tables,
        ];

        return view('index_admin', $data);
    }

    public  static function logout(): RedirectResponse
    {
        self::setAuthData('', [], true);

        return Redirect::to('/'); // Redirect to home or login page
    }

    public function getTables(Request $request): JsonResponse
    {
        $tables = Account::getActives();
        return self::success("Tabelas ativas", ['tables' => $tables]);
    }

    public function closeTable(Request $request): object
    {
        $table_number = $request->get('table_number');
        $account_id   = $request->get('account_id');

        $is_table = ($table_number && is_numeric($table_number));

        if ($is_table && !$table_number) return self::error('Número da mesa não informado.');
        if (!$is_table && !$account_id) return self::error('ID do cliente não informado.');

        if ($is_table)  return Account::closeTableByNumber($table_number);
        if (!$is_table) return Account::closeAccount($account_id);

        return self::error('Parâmetros inválidos.');
    }
}
