<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsFromContainerSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('container_suppliers', function (Blueprint $table) {
            $table->dropColumn([
                'bsa_3_m_3_price',
                'bsa_6_m_3_price',
                'bsa_10_m_3_price',
                'bsa_20_m_3_price',
                'hout_3_m_3_price',
                'hout_6_m_3_price',
                'hout_10_m_3_price',
                'hout_20_m_3_price',
                'debris_3_m_3_price',
                'debris_6_m_3_price',
                'debris_10_m_3_price',
                'debris_20_m_3_price',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('container_suppliers', function (Blueprint $table) {
            $table->string('bsa_3_m_3_price')->nullable();
            $table->string('bsa_6_m_3_price')->nullable();
            $table->string('bsa_10_m_3_price')->nullable();
            $table->string('bsa_20_m_3_price')->nullable();
            $table->string('hout_3_m_3_price')->nullable();
            $table->string('hout_6_m_3_price')->nullable();
            $table->string('hout_10_m_3_price')->nullable();
            $table->string('hout_20_m_3_price')->nullable();
            $table->string('debris_3_m_3_price')->nullable();
            $table->string('debris_6_m_3_price')->nullable();
            $table->string('debris_10_m_3_price')->nullable();
            $table->string('debris_20_m_3_price')->nullable();
        });
    }
}
