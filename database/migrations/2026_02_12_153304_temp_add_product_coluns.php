<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product', function (Blueprint $table) {
            $table->decimal('p_purchase_price', 8, 2)->after('p_price');
            $table->decimal('p_expected_profit', 8, 2)->default(0.00)->after('p_purchase_price');

            $table->unsignedInteger('p_quantity_purchase')->default(1)->after('p_image');
            $table->unsignedInteger('p_quantity_sales')->default(1)->after('p_quantity_purchase');

            $table->unsignedBigInteger('p_sv_purchasing_unit_pd_fk')->after('p_sv_category_pd_fk')->default(37);
            $table->unsignedBigInteger('p_sv_sales_unit_pd_fk')->after('p_sv_purchasing_unit_pd_fk')->default(37);

            $table->foreign('p_sv_purchasing_unit_pd_fk')->references('sv_id')->on('simple_values');
            $table->foreign('p_sv_sales_unit_pd_fk')->references('sv_id')->on('simple_values');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product', function (Blueprint $table) {
            //
        });
    }
};
