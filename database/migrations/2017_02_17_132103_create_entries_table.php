<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->integer('branch_office_id')->index();
            $table->integer('guest_type_id')->index();
            $table->dateTime('entry_in');
            $table->dateTime('entry_out')->nullable();
            $table->string('guest_document_id', 20)->index();
            $table->string('guest_full_name', 200);
            $table->string('leader_full_name', 200)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
