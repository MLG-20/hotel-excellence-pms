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
        Schema::create('slider_heros', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('sous_titre')->nullable();
            $table->string('image')->nullable();
            $table->string('bouton_texte')->nullable()->default('Réserver maintenant');
            $table->string('bouton_lien')->nullable()->default('/chambres');
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slider_heros');
    }
};
