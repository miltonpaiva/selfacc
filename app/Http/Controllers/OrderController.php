<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Account;

class OrderController extends Controller
{
    public function upInsertOrder(Request $request): object
    {
        $is_update = $request->has('id') && !empty($request->get('id'));
        $response = self::newOrUpdateModel($request, new Order(), null, $is_update);

        $data = $response->getData(true);

        if (!$data || !$data['success']) return $response;

        Account::updateTotal($data['data']['account_id']);

        $new_data = $data['data'];
        $new_data['orders'] = Order::getActivesByTableNumber($data['data']['table_number']);

        if($request->get('is_admin')){
            $tables = Account::getActives();
            $new_data['tables'] = $tables;
        }

        return self::success($data['message'], $new_data);
    }
}
