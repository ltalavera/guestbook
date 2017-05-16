<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVisitorIdToEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entries', function($table) {
            $table->integer('visitor_id')->unsigned()->after('id');
            //$table->foreign('visitor_id')->references('id')->on('visitors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entries', function($table) {
            $table->dropColumn('visitor_id');
        });
    }
}