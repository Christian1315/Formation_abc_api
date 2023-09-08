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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fret')
                ->nullable()
                ->constrained(table: 'frets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer("price");
            $table->integer("avance")->nullable();
            $table->string("charg_date");
            $table->longText("info");
            $table->boolean("reserved")->nullable();

            ###CELUI QUI RESERVE
            $table->foreignId('owner')
                ->nullable()
                ->constrained('users', "id")
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
        Schema::dropIfExists('reservations');
    }
};
