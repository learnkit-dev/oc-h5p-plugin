<?php namespace LearnKit\H5p\Updates;

use October\Rain\Database\Updates\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH5pContentsLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h5p_contents_libraries', function (Blueprint $table) {
            $table->bigInteger('content_id')->unsigned();
            $table->bigInteger('library_id')->unsigned();
            $table->string('dependency_type', 31);
            $table->smallInteger('weight')->unsigned()->default(0);
            $table->boolean('drop_css');
            $table->primary(['content_id', 'library_id', 'dependency_type'], 'fk_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('h5p_contents_libraries');
    }
}
