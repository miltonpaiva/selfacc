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
        Schema::create('music_queue', function (Blueprint $table) {
            // Chave Primária (PK)
            $table->id('mq_id'); // int, not null, unique, auto-increment

            // Colunas de Dados
            $table->string('mq_uri');       // string, not null
            $table->string('mq_code');      // string, not null
            $table->string('mq_str');      // string, not null
            $table->integer('mq_position');  // int, not null
            $table->boolean('mq_is_auction'); // bool, not null

            // Chaves Estrangeiras (FK)
            // Assumimos que as tabelas referenciadas (account, customer, simple_values) existem ou serão criadas.
            $table->unsignedBigInteger('mq_account_fk');
            $table->unsignedBigInteger('mq_customer_fk');
            $table->unsignedBigInteger('mq_sv_status_mq_fk');

            // Definição das Chaves Estrangeiras (Opcional, mas recomendado para integridade)
            $table->foreign('mq_account_fk')->references('a_id')->on('account');
            $table->foreign('mq_customer_fk')->references('c_id')->on('customer');
            // Assumindo que a tabela de status é 'simple_values' com a chave 'sv_id' conforme seu exemplo inicial.
            $table->foreign('mq_sv_status_mq_fk')->references('sv_id')->on('simple_values');

            // Timestamps Personalizados
            // date/timestamp sem horário (idealmente use $table->timestamp() para datas completas)
            $table->timestamp('mq_dt_created')->useCurrent();
            $table->timestamp('mq_dt_updated')->useCurrent()->useCurrentOnUpdate();
            // NOTA: No modelo original, as datas são 'date'. No Laravel, 'timestamp' é mais comum, mas se for realmente apenas a data, troque para $table->date(). Mantenho 'timestamp' para compatibilidade com useCurrent().
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_queue');
    }
};