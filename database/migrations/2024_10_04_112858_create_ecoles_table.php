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
        Schema::create('ecoles', function (Blueprint $table) {
            $table->id();
            
            $table->string('nom');
            $table->enum('type_etablissement', ['Université', 'École', 'Institut', 'Centre de Formation']);
            $table->text('adresse');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('site_web')->nullable();
            $table->string('responsable')->nullable();

            $table->year('annee_fondation')->nullable();

            $table->integer('nombre_etudiants')->default(0);
            $table->integer('nombre_formations')->default(0);

            $table->enum('status', ['actif', 'inactif'])->default('actif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecoles');
    }
};
