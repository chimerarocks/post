<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToCommentsTable
{
	public function up()
	{
		Schema::table('chimerarocks_comments', function (Blueprint $table) {
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::table('chimerarocks_comments', function (Blueprint $table) {
			$table->dropColumn('deleted_at');
		});
	}
}