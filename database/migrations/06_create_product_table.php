<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            // Chave Primária (PK)
            $table->id('p_id'); // Cria 'p_id' como UNSIGNED BIGINT auto-increment.

            // Colunas de Dados
            $table->string('p_name');         // string, not null
            // Decimal: 8 dígitos no total, 2 casas decimais (ex: 999999.99)
            $table->decimal('p_price', 8, 2); // decimal:8,2, not null
            $table->decimal('p_purchase_price', 8, 2); // decimal:8,2, not null
            $table->decimal('p_expected_profit', 8, 2)->default(0.00);
            $table->string('p_description')->nullable();  // string
            $table->string('p_image')->nullable();  // string

            $table->unsignedInteger('p_quantity_purchase')->default(1);
            $table->unsignedInteger('p_quantity_sales')->default(1);

            // Chave Estrangeira (FK)
            // Referencia a tabela 'simple_values' (chave 'sv_id') para a categoria
            $table->unsignedBigInteger('p_sv_category_pd_fk');
            $table->unsignedBigInteger('p_sv_purchasing_unit_pd_fk')->default(37);
            $table->unsignedBigInteger('p_sv_sales_unit_pd_fk')->default(37);

            // Timestamps Personalizados
            $table->timestamp('p_dt_created')->useCurrent();
            $table->timestamp('p_dt_updated')->useCurrent()->useCurrentOnUpdate();

            // Definição da Chave Estrangeira
            // Assumindo a coluna de referência 'sv_id' na tabela 'simple_values'
            $table->foreign('p_sv_category_pd_fk')->references('sv_id')->on('simple_values');
            $table->foreign('p_sv_purchasing_unit_pd_fk')->references('sv_id')->on('simple_values');
            $table->foreign('p_sv_sales_unit_pd_fk')->references('sv_id')->on('simple_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
};