<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function upInsertProduct(Request $request): object
    {
        $is_update = $request->has('id') && !empty($request->get('id'));
        return self::newOrUpdateModel($request, new Product(), null, $is_update);
    }
}
