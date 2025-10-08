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
        Schema::create('music_auction', function (Blueprint $table) {
            // Chave Primária (PK)
            $table->id('ma_id'); // Cria 'ma_id' como UNSIGNED BIGINT auto-increment.

            // Chaves Estrangeiras (FK)
            // Referencia a tabela 'music_queue' (chave 'mq_id')
            $table->unsignedBigInteger('ma_music_queue_fk')->unique(); // Assumindo 1:1 com a fila
            // Referencia a tabela 'simple_values' (chave 'sv_id') para o status do leilão
            $table->unsignedBigInteger('ma_sv_status_ma_fk');

            // Colunas de Dados
            // Usando 'float' conforme o diagrama, mas 'decimal' é melhor para valores monetários.
            $table->decimal('ma_offer', 8, 2); // float, not null

            // Timestamps Personalizados
            $table->timestamp('ma_dt_created')->useCurrent();
            $table->timestamp('ma_dt_updated')->useCurrent()->useCurrentOnUpdate();

            // Definição das Chaves Estrangeiras
            // onDelete('cascade') é recomendado aqui: se o item da fila for deletado, o leilão associado também deve ser.
            $table->foreign('ma_music_queue_fk')->references('mq_id')->on('music_queue')->onDelete('cascade');

            // Assumindo a coluna de referência 'sv_id' na tabela 'simple_values'
            $table->foreign('ma_sv_status_ma_fk')->references('sv_id')->on('simple_values');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_auction');
    }
};