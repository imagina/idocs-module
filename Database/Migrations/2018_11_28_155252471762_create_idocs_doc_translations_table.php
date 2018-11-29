<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdocsDocTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idocs__doc_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('title');
            $table->text('slug');
            $table->string('url');
            $table->text('description');
            $table->integer('doc_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['doc_id', 'locale']);
            $table->foreign('doc_id')->references('id')->on('idocs__docs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('idocs__doc_translations', function (Blueprint $table) {
            $table->dropForeign(['doc_id']);
        });
        Schema::dropIfExists('idocs__doc_translations');
    }
}
