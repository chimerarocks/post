<?php

use ChimeraRocks\Post\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChimerarocksPostsTable
{
	public function up()
	{
		Schema::create('chimerarocks_posts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->string('slug');
			$table->text('content');
			$table->integer('state')->default(Post::STATE_DRAFT);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('chimerarocks_posts');
	}
}