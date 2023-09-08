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
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fret')
                ->nullable()
                ->constrained(table: 'frets')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('transport')
                ->nullable()
                ->constrained(table: 'transports')
                ->onUpdate('cascade')
                ->onDelete('cascade');

                $table->string("duration");
            $table->integer("avis")->nullable();
            $table->text("transport_letter")->nullable();

            $table->boolean("delivered")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};
