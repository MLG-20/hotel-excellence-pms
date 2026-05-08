<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis_clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('message');
            $table->unsignedTinyInteger('note')->default(5);
            $table->string('photo')->nullable();
            $table->unsignedSmallInteger('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis_clients');
    }
};
