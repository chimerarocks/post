<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChimerarocksCommentsTable
{
	public function up()
	{
		Schema::create('chimerarocks_comments', function (Blueprint $table) {
			$table->increments('id');
			$table->text('content');
			$table->integer('post_id');
			$table->foreign('post_id')->references('id')->on('chimerarocks_posts');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('chimerarocks_comments');
	}
}