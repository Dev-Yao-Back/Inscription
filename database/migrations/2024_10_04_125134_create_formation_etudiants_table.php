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
        Schema::create('formation_etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('formation_id')->constrained('formations')->onDelete('cascade');
            $table->decimal('prix_a_paye', 10, 0,' ');

            $table->decimal('montant', 10, 0,' ');
            $table->decimal('montant_reste', 10, 0,' ');
            $table->date('date'); // Carte de crédit, Mobile Money, etc.
            $table->string('moyen_paiement'); // Carte de crédit, Mobile Money, etc.
            $table->string('status')->default('en attente'); // 'en attente', 'réussi', 'échoué'
            $table->string('validation')->default('non validé');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation_etudiants');
    }
};