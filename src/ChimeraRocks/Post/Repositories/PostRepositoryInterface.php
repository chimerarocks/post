<?php

namespace ChimeraRocks\Post\Repositories;

use ChimeraRocks\Database\Contracts\CriteriaCollectionInterface;
use ChimeraRocks\Database\Contracts\RepositoryInterface;

interface PostRepositoryInterface extends RepositoryInterface, CriteriaCollectionInterface
{
	public function updateState($id, $state);

	public function findByCategory($id);
}