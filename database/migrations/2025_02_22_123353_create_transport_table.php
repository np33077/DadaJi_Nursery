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
        Schema::create('transport', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type'); // e.g., Truck, Van
            $table->string('driver_name');
            $table->string('contact')->unique();
            $table->decimal('estimated_cost', 10, 2);
            $table->date('trip_date');
            $table->foreignId('delivery_id')->constrained('deliveries')->onDelete('cascade');
            $table->enum('status', ['Scheduled', 'In Progress', 'Completed'])->default('Scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport');
    }
};
