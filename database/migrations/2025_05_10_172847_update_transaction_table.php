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
        //
        Schema::table('transactions', function (Blueprint $table) {
            // Remove old columns
            $table->dropColumn(['user_id', 'before_balance' , 'after_balance']);

            // Add new column after 'reference_type'
            $table->string('reference_type')->after('reference_id');
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
