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
        Schema::create('marchandises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fret')
                ->nullable()
                ->constrained('frets', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreignId('type')
                ->nullable()
                ->constrained('marchandises_type', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->integer('weight');
            $table->integer('length');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marchandises');
    }
};
