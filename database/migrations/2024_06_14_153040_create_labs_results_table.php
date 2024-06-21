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
        Schema::create('labs_results', function (Blueprint $table) {
            $table->increments('id'); // Primary key, auto-increment
            $table->string('LName', 255); // varchar(25)
            $table->string('LDescription', 255); // varchar(255)
            $table->string('LResult', 255); // varchar(255)
            $table->integer('doctor_id')->nullable(false);; // unsigned integer
            $table->integer('patient_id')->nullable(false);; // unsigned integer
            $table->timestamps(); // Adds created_at and updated_at columns

            // Foreign key constraints
            $table->foreign('doctor_id')->references('EmployeeID')->on('employee');
            $table->foreign('patient_id')->references('Pat_ID')->on('patient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labs_results');
    }
};
