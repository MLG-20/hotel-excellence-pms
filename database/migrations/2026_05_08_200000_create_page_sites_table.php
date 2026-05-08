<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sites', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('titre');
            $table->text('contenu')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email_contact')->nullable();
            $table->text('adresse')->nullable();
            $table->text('horaires')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sites');
    }
};
