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
        Schema::create('notifications', function (Blueprint $table) {
            // Chave Primária (PK)
            $table->id('n_id'); // Cria 'n_id' como UNSIGNED BIGINT auto-increment.

            // Colunas de Dados
            $table->text('n_text'); // 'text' é mais apropriado para conteúdo de notificação longo, embora o diagrama diga 'text'.
            $table->string('n_route')->nullable(); // string, não tem 'not null'

            // Colunas com Valor Default
            $table->string('n_confirm_text')->default("Confirmar");
            $table->boolean('n_is_confirmable')->default(false); // bool, default:false

            // ID Específico (genérico para ligar a outras entidades)
            $table->unsignedBigInteger('n_specific_id')->nullable(); // int, não tem 'not null', então é nullable

            // Chaves Estrangeiras (FK)
            // Referencia 'simple_values' para Status
            $table->unsignedBigInteger('n_sv_status_nt_fk');
            // Referencia 'simple_values' para Tópico/Tipo
            $table->unsignedBigInteger('n_sv_topic_nt_fk');

            // Timestamps Personalizados
            $table->timestamp('n_dt_created')->useCurrent();
            $table->timestamp('n_dt_updated')->useCurrent()->useCurrentOnUpdate();

            // Definição das Chaves Estrangeiras
            // Assumindo a coluna de referência 'sv_id' na tabela 'simple_values'
            $table->foreign('n_sv_status_nt_fk')->references('sv_id')->on('simple_values');
            $table->foreign('n_sv_topic_nt_fk')->references('sv_id')->on('simple_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};