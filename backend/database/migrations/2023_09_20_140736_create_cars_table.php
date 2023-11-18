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
        Schema::create('cars', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users');
            $table->string('make');
            $table->string('model');
            $table->string('vin')->unique();
            $table->integer('year_of_manufacture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
