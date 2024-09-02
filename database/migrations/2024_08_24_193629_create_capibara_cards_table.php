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
        Schema::create('capibara_cards', function (Blueprint $table) {
            #permite generar la estructura de la tabla $table
            $table->id();
            $table->string('nameCard', 255);
            $table->string('description', 255);
            $table->string('image');
            //solo acepta numeros enteros positivos
            $table->unsignedInteger('rarity'); // 0: common, 1: rare, 2: epic, 3: legendary 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capibara_cards');
    }
};
