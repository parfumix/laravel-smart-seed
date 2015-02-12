<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Schema;

class AddSeedsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('seeds', function(Blueprint $table) {
            $table->increments('id');
            $table->string('seed');
            $table->string('env');
            $table->integer('batch');
            $table->string('hash');
            $table->timestamps();
            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('seeds');
    }

}
