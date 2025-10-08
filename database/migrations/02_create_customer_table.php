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
        Schema::create('customer', function (Blueprint $table) {
            // Chave Primária (PK)
            // 'int, not null, unique' é implementado por 'id()' que cria um UNSIGNED BIGINT auto-increment.
            $table->id('c_id');

            // Colunas de Dados
            $table->string('c_name');        // string, not null
            $table->integer('c_code')->unique(); // int, not null
            $table->string('c_phone')->nullable();   // string, nullable (pois não tem 'not null')

            // Timestamps Personalizados
            // Seguindo o seu padrão de timestamps personalizados com uso de useCurrent()
            // e assumindo que você quer a data completa (timestamp) para gerenciamento.
            $table->timestamp('c_dt_created')->useCurrent();
            $table->timestamp('c_dt_updated')->useCurrent()->useCurrentOnUpdate();

            // Adicionando um índice para buscas mais rápidas no nome.
            $table->index('c_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer');
    }
};