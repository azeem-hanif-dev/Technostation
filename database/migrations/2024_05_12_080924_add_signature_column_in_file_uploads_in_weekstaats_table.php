<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatureColumnInFileUploadsInWeekstaatsTable extends Migration
{
    public function up(): void
    {
        Schema::table('file_uploads_in_weekstaats', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by_id')->after('FileName')->nullable();
            $table->string('signature')->after('approved_by_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('file_uploads_in_weekstaats', function (Blueprint $table) {
            $table->dropColumn('approved_by_id','signature');
        });
    }
}
