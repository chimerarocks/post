<?php

namespace ChimeraRocks\Post\Repositories;

use ChimeraRocks\Category\Criterias\Eloquent\FindByCategoryCriteria;
use ChimeraRocks\Category\Repositories\CategoryRepositoryInterface;
use ChimeraRocks\Post\Models\Post;
use ChimeraRocks\Database\AbstractEloquentRepository;

class PostRepositoryEloquent extends AbstractEloquentRepository implements PostRepositoryInterface
{
	private $categoryRepository;

	public function model()
	{
		return Post::class;
	}

	public function __construct(CategoryRepositoryInterface $categoryRepository)
	{
		parent::__construct();
		$this->categoryRepository = $categoryRepository;
	}

	public function create(array $data)
	{
		$post = parent::create($data);
		if ($data['categories']) {
			foreach ($data['categories'] as $category) {
				$post->categories()->save($this->categoryRepository->find($category));
			}
		}
		return $post;
	}

	public function updateState($id, $state)
	{
		$post = $this->find($id);
		$post->state = $state;
		$post->save();
		return $post;
	}

	public function findByCategory($id, $columns = ['*'])
	{
		$this->clearCriteria();
		$this->getByCriteria(new FindByCategoryCriteria($id));
		return $this->all($columns);
	}
}