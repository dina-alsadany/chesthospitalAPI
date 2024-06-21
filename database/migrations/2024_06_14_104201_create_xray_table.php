<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXrayTable extends Migration
{
    public function up()
    {
        Schema::create('xray', function (Blueprint $table) {
            $table->id('XRay_ID');
            $table->json('XResult');
            $table->string('XImage');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('xray');
    }
}
