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
        Schema::create('order', function (Blueprint $table) {
            // Chave Primária (PK)
            $table->id('o_id'); // Cria 'o_id' como UNSIGNED BIGINT auto-increment.

            // Chaves Estrangeiras (FK)
            // Referencia a tabela 'account' (chave 'a_id')
            $table->unsignedBigInteger('o_account_fk');
            // Referencia a tabela 'product' (chave 'p_id')
            $table->unsignedBigInteger('o_product_fk');

            // Colunas de Dados
            $table->unsignedInteger('o_quantity'); // int, not null (assumindo que quantidade é sempre positiva)
            $table->string('o_observations');
            // Decimal: 8 dígitos no total, 2 casas decimais (conforme o diagrama)
            $table->decimal('o_total', 8, 2);    // decimal:8,2, not null

            // Timestamps Personalizados
            $table->timestamp('o_dt_created')->useCurrent();
            $table->timestamp('o_dt_updated')->useCurrent()->useCurrentOnUpdate();

            // Definição das Chaves Estrangeiras
            // Se a conta ou o produto forem deletados, os itens do pedido associado devem ser tratados (cascade/set null).
            $table->foreign('o_account_fk')->references('a_id')->on('account')->onDelete('cascade');
            $table->foreign('o_product_fk')->references('p_id')->on('product')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
};