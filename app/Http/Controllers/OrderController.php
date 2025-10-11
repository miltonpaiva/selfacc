<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function upInsertOrder(Request $request): object
    {
        $is_update = $request->has('id') && !empty($request->get('id'));
        $response = self::newOrUpdateModel($request, new Order(), null, $is_update);

        $data = $response->getData(true);

        if (!$data) return $response;

        $auth_data = self::getAuthData();

        $new_data = $data['data'];
        $new_data['orders'] = Order::getActivesByTableNumber($auth_data['account']['table_number']);

        return self::success($data['message'], $new_data);
    }
}
