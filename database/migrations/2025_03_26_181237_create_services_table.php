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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('car_id'); // Azért nem hivatkozok a cars táblára, mert ez nem a cars táblában lévő id-ra mutat, hanem a cars táblában lévő client-hez tartozó car_id-jára
            $table->integer('log_number');
            $table->string('event');
            $table->dateTime('event_time')->nullable();
            $table->integer('document_id');
            $table->timestamps();

            $table->unique(['client_id', 'car_id', 'log_number'], 'unique_client_car_log_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
