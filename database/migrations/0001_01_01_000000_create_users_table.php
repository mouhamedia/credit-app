<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable(); // email facultatif si pas utilisé pour boutiquier
            $table->string('phone')->nullable();
            $table->string('password')->nullable(); // admin/boutiquier avec mot de passe
            $table->enum('role', ['admin', 'boutiquier'])->default('boutiquier');
            $table->boolean('status')->default(true); // true = actif, false = bloqué
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
