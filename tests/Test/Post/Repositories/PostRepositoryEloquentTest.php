<?php

namespace Test\Post\Models;

use ChimeraRocks\Category\Models\Category;
use ChimeraRocks\Category\Repositories\CategoryRepositoryEloquent;
use ChimeraRocks\Post\Repositories\PostRepositoryEloquent;
use Illuminate\Validation\Validator;
use Mockery;
use Test\AbstactTestCase;

class PostRepositoryEloquentTest extends AbstactTestCase
{
	private $postRepository;

	public function setUp()
	{
		parent::setUp();
		$this->migrate();
	}

	public function __construct()
	{
		parent::__construct();
		$categoryRepository = new CategoryRepositoryEloquent();
		$this->postRepository = new PostRepositoryEloquent($categoryRepository);
	}

	public function test_can_save_a_category_to_a_post()
	{
		Category::create(['name' => 'Category', 'active' => true]);

		$data = ['title' => 'my post 1', 'content' => 'Content', 'categories' => [1]];

		$this->postRepository->create($data);
		$post = $this->postRepository->find(1);

		$this->assertCount(1, $post->categories);
		$this->assertEquals('Category', $post->categories->first()->name);
	}

	public function test_can_retrieve_all_posts_by_category()
	{
		Category::create(['name' => 'Category', 'active' => true]);
		Category::create(['name' => 'Category2', 'active' => true]);

		$data = ['title' => 'my post 1', 'content' => 'Content', 'categories' => [1]];
		$data2 = ['title' => 'my post 2', 'content' => 'Content', 'categories' => [1,2]];

		$this->postRepository->create($data);
		$this->postRepository->create($data2);
		$posts = $this->postRepository->findByCategory(1);
		$this->assertCount(2, $posts);
		$this->assertEquals('Category', $posts[0]->categories->first()->name);
		$posts = $this->postRepository->findByCategory(2);
		$this->assertCount(1, $posts);
		$this->assertEquals('Category', $posts[0]->categories->first()->name);
	}
}