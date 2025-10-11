<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function upInsertCustomer(Request $request): object
    {
        $is_update = $request->has('id') && !empty($request->get('id'));
        return self::newOrUpdateModel($request, new Customer(), null, $is_update);
    }

}
