<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seed_purchasing', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['transaction_id']);

            // Add new column after 'receiver_id'
            $table->integer('receiver_id')->after('purchase_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
