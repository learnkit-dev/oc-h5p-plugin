<?php namespace Kloos\H5p\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateLibrariesTable extends Migration
{
    public function up()
    {
        Schema::create('kloos_h5p_libraries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kloos_h5p_libraries');
    }
}
