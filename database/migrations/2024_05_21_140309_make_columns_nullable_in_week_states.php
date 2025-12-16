<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeColumnsNullableInWeekStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `week_states`
                       MODIFY `delay_date` DATE NULL,
                       MODIFY `receive_date` DATE NULL,
                       MODIFY `invoice_date` DATE NULL,
                       MODIFY `invoice_no` VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `week_states`
                       MODIFY `delay_date` DATE NOT NULL,
                       MODIFY `receive_date` DATE NOT NULL,
                       MODIFY `invoice_date` DATE NOT NULL,
                       MODIFY `invoice_no` VARCHAR(255) NOT NULL');
    }
}
