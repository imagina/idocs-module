<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdocsDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idocs__docs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            // fields
            $table->text('title');
            $table->string('url');
            $table->text('description');
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned()->nallable();
            $table->integer('role')->unsigned()->nullable();
            $table->integer('status')->default(0)->unsigned();


            $table->foreign('category_id')->references('id')->on('iblog__categories')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on(config('auth.table', 'users'))->onDelete('restrict');
            $table->text('options')->default('')->nullable();

            // Your fields
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
        Schema::dropIfExists('idocs__docs');
    }
}
