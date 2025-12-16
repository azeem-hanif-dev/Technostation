<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerSuppliersTable extends Migration
{
    public function up(): void
    {
        Schema::create('container_suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name');
            $table->string('code');
            $table->string('telephone')->nullable();
            $table->string('mobile');
            $table->string('fax');
            $table->string('email');
            $table->string('address');
            $table->string('post_code');
            $table->string('city');
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
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('container_suppliers');
    }
}
