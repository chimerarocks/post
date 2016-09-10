<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToPostsTable
{
	public function up()
	{
		Schema::table('chimerarocks_posts', function (Blueprint $table) {
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::table('chimerarocks_posts', function (Blueprint $table) {
			$table->dropColumn('deleted_at');
		});
	}
}