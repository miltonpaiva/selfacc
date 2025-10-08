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
        Schema::create('user', function (Blueprint $table) {
            // Chave Primária (PK)
            $table->id('u_id'); // Cria 'u_id' como UNSIGNED BIGINT auto-increment.

            // Colunas de Autenticação e Dados
            $table->string('u_name');        // string, not null
            $table->string('u_email')->unique(); // string, not null, deve ser único para login
            // Coluna para a senha (geralmente 255 caracteres é suficiente para hashes)
            $table->string('u_pass');        // string, not null

            // Chave Estrangeira (FK)
            // Referencia a tabela 'simple_values' (chave 'sv_id') para o tipo de usuário
            $table->unsignedBigInteger('u_sv_type_us_fk');

            // Timestamps Personalizados
            $table->timestamp('u_dt_created')->useCurrent();
            $table->timestamp('u_dt_updated')->useCurrent()->useCurrentOnUpdate();

            // Definição da Chave Estrangeira
            // Assumindo a coluna de referência 'sv_id' na tabela 'simple_values'
            $table->foreign('u_sv_type_us_fk')->references('sv_id')->on('simple_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
};