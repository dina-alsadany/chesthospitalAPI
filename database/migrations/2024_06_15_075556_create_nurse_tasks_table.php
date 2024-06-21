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
        Schema::create('nurse_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description', 255);
            $table->dateTime('deadline');
            $table->integer('doctorId');
            $table->integer('patientId');
            $table->foreign('doctorId')->references('EmployeeID')->on('employee')->onDelete('cascade');
            $table->foreign('patientId')->references('Pat_ID')->on('patient')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurse_tasks');
    }
};
