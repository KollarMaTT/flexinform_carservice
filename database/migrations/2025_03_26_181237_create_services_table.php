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
            $table->foreignId('car_id')->constrained('cars');
            $table->integer('log_number');
            $table->string('event');
            $table->dateTime('event_time');
            $table->integer('document_id');
            $table->timestamps();

            $table->unique(['client_id','car_id'], 'unique_client_car');
            $table->unique(['client_id','car_id','log_number'], 'unique_client_car_log_number');
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
