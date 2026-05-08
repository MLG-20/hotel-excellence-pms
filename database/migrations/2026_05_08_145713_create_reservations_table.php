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
            $table->foreignId('client_id')->constrained('clients_hotel')->onDelete('restrict');
            $table->foreignId('chambre_id')->constrained('chambres')->onDelete('restrict');
            $table->date('date_arrivee');
            $table->date('date_depart');
            $table->integer('adultes')->default(1);
            $table->integer('enfants')->default(0);
            $table->enum('statut', ['en_attente', 'confirmee', 'annulee', 'checkin', 'checkout'])->default('en_attente');
            $table->enum('source', ['direct', 'booking', 'airbnb', 'telephone', 'agence'])->default('direct');
            $table->text('notes')->nullable();
            $table->decimal('montant_total', 10, 2)->nullable();
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
