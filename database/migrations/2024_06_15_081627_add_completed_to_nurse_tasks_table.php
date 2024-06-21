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
        Schema::table('nurse_tasks', function (Blueprint $table) {
            $table->boolean('completed')->default(false); // Assuming 'completed' is a boolean field
        });
    }

    public function down()
    {
        Schema::table('nurse_tasks', function (Blueprint $table) {
            $table->dropColumn('completed');
        });
    }
};
