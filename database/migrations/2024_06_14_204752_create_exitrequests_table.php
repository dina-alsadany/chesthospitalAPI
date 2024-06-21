<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExitrequestsTable extends Migration
{
    public function up()
    {
        Schema::create('exitrequests', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->integer('doctor_id');
            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            // Foreign key constraints
            $table->foreign('patient_id')->references('Pat_ID')->on('patient');
            $table->foreign('doctor_id')->references('EmployeeID')->on('employee');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exitrequests');
    }
}
