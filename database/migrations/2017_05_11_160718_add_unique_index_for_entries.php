<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueIndexForEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entries', function($table) {
            $table->unique(['branch_office_id', 'guest_document_id', 'entry_in']);
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
            $table->dropUnique(['branch_office_id', 'guest_document_id', 'entry_in']);
        });
    }
}
