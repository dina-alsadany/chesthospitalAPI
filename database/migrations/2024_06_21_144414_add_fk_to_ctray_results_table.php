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
        Schema::table('ctray_results', function (Blueprint $table) {
            $table->foreign('doctor_id')->references('DoctorID')->on('doctor');
            $table->foreign('patient_id')->references('Pat_ID')->on('patient');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ctray_results', function (Blueprint $table) {
            //
        });
    }
};
