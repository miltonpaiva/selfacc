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
        Schema::create('simple_values', function (Blueprint $table) {
            $table->id('sv_id');

            $table->string('sv_title');
            $table->string('sv_group');

            $table->timestamp('sv_dt_created')->useCurrent();
            $table->timestamp('sv_dt_updated')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simple_values');
    }
};
