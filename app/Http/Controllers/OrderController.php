<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Account;
use App\Models\SimpleValues as SV;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function upInsertOrder(Request $request): object
    {
        $is_update = $request->has('id') && !empty($request->get('id'));

        $initial_status_id = SV::getValueId('status_or', 'Novo');
        $request->merge(['status_id' => $initial_status_id]);

        $response  = self::newOrUpdateModel($request, new Order(), null, $is_update);

        $data = $response->getData(true);

        if (!$data || !$data['success']) return $response;

        Account::updateTotal($data['data']['account_id']);
        Account::updateStatus($data['data']['account_id']);

        $new_data['new']    = $data['data'];
        $new_data['orders'] = Order::getActivesByTableNumber($data['data']['table_number']);

        if($request->get('is_admin')){
            $tables = Account::getActives();
            $new_data['tables'] = $tables;
        }

        return self::success($data['message'], $new_data);
    }

    public function conclude(Request $request): JsonResponse
    {
        $order_id = $request->get('order_id');

        if (!$order_id) return self::error('ID do pedido não informado.');

        $order = Order::find($order_id);

        if (!$order) return self::error('Pedido não encontrado.');

        $concluded_status_id = SV::getValueId('status_or', 'Concluído');

        $order->o_sv_status_or_fk = $concluded_status_id;
        $saved = $order->save();

        if (!$saved) return self::error('Falha ao concluir o pedido.');

        $tables = Account::getActives();

        return self::success('Pedido concluído com sucesso.', ['tables' => $tables]);
    }

    public function remove(Request $request): JsonResponse
    {
        $order_id = $request->get('order_id');

        if (!$order_id) return self::error('ID do pedido não informado.');

        $order = Order::find($order_id);

        if (!$order) return self::error('Pedido não encontrado.');

        $account_id = $order->o_account_fk;

        $result = $order->delete();

        Account::updateTotal($account_id);

        if (!$result) return self::error('Pedido não pôde ser removido.');

        $tables = Account::getActives();

        return self::success('Pedido removido com sucesso.', ['tables' => $tables]);
    }
}
