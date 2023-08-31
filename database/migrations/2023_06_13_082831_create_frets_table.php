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
        Schema::create('frets', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('nature');
            $table->string('vol_or_quant');
            $table->string('charg_date');
            $table->string('charg_location');
            $table->string('charg_destination');
            $table->integer('axles_num');
            $table->string('fret_img');
            $table->foreignId('owner')
                ->nullable()
                ->constrained('users', "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreignId('status')
                ->nullable()
                ->constrained("fret_statuses", "id")
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frets');
    }
};
