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
        Schema::create('account', function (Blueprint $table) {
            // Chave Primária (PK)
            $table->id('a_id'); // Cria 'a_id' como UNSIGNED BIGINT auto-increment.

            // Chaves Estrangeiras (FK)
            // Referencia a tabela 'customer' criada anteriormente (chave 'c_id')
            $table->unsignedBigInteger('a_customer_fk');
            // Referencia a tabela 'simple_values' (chave 'sv_id')
            $table->unsignedBigInteger('a_sv_status_ac_fk');

            // Colunas de Dados
            $table->integer('a_table_number'); // int, not null
            // 'float' no diagrama é traduzido para 'float' ou 'decimal' no Laravel.
            // Usando 'float' conforme o diagrama, mas 'decimal' é frequentemente mais preciso para moeda.
            $table->decimal('a_total_consumed', 8, 2); // float, not null

            // Timestamps Personalizados (Datas)
            $table->timestamp('a_dt_created')->useCurrent();
            $table->timestamp('a_dt_updated')->useCurrent()->useCurrentOnUpdate();

            // Definição das Chaves Estrangeiras
            // onDelete('cascade') é opcional, mas garante que a conta seja excluída se o cliente for.
            $table->foreign('a_customer_fk')->references('c_id')->on('customer')->onDelete('cascade');

            // Assumindo que a coluna de referência é 'sv_id' na tabela 'simple_values'
            $table->foreign('a_sv_status_ac_fk')->references('sv_id')->on('simple_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account');
    }
};