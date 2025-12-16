<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContainerOperationIdToContainerWasteBreakdownTable extends Migration
{
    public function up()
    {
        Schema::table('container_waste_breakdown', function (Blueprint $table) {
            $table->unsignedBigInteger('container_operation_id')->nullable()->after('container_type_id');


        });
    }

    public function down()
    {
        Schema::table('container_waste_breakdown', function (Blueprint $table) {
            $table->dropColumn('container_operation_id');
        });
    }
}
