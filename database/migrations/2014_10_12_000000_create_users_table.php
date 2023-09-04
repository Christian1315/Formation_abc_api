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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();

            $table->string('company_name')->nullable();
            $table->string('denomination')->nullable();
            
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');

            $table->string('ifu')->nullable();
            $table->string('rccm')->nullable();

            $table->string('active_compte_code')->nullable();
            $table->string('compte_actif')->default(false);

            $table->string('pass_code')->nullable();
            $table->string('pass_code_active')->default(true);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
