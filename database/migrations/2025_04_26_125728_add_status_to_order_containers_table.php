<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToOrderContainersTable extends Migration
{
    public function up()
    {
        Schema::table('order_containers', function (Blueprint $table) {
            $table->enum('status', [
                'open',
                'in progress',
                'send to supplier',
                'pick up',
                'picked',
                'canceled',
                'close',
            ])->default('open')->after('comments');
        });
    }

    public function down()
    {
        Schema::table('order_containers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
