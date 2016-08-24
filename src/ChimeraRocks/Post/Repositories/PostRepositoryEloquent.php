<?php

namespace ChimeraRocks\Post\Repositories;

use ChimeraRocks\Post\Models\Post;
use ChimeraRocks\Post\Repositories\PostRepositoryInterface;
use ChimeraRocks\Database\AbstractEloquentRepository;

class PostRepositoryEloquent extends AbstractEloquentRepository implements PostRepositoryInterface
{
	public function model()
	{
		return Post::class;
	}
}