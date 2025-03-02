<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seed_sowing', function (Blueprint $table) {
            $table->id();
            $table->string('plant_name');
            $table->string('variety');
            $table->integer('sowing_duration'); // Duration in days (e.g., 30 for Tomato)
            $table->enum("status", ["Y", "N"])->default("Y"); // Y for sowing done, N for sowing pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seed_sowing');
    }
};
