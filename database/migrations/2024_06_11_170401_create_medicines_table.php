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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->integer('doctor_id');
            $table->integer('patient_id');
            $table->enum('num_doses', [1, 2, 3]); // Column for the number of doses
            $table->timestamps(); // Assuming maximum three doses

            // Foreign key constraints
            $table->foreign('doctor_id')->references('DoctorID')->on('doctor');
            $table->foreign('patient_id')->references('Pat_ID')->on('patient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
