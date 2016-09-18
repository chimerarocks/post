<?php

namespace Test\Post\Models;

use ChimeraRocks\Category\Models\Category;
use ChimeraRocks\Category\Repositories\CategoryRepositoryEloquent;
use ChimeraRocks\Post\Models\Post;
use ChimeraRocks\Post\Repositories\PostRepositoryEloquent;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use Mockery;
use Test\AbstactTestCase;

class PostTest extends AbstactTestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->migrate();
	}

	public function __construct()
	{
		parent::__construct();
	}

	public function test_inject_validator_in_post_model()
	{
		$post = new Post();
		$validator = Mockery::mock(Validator::class);
		$post->setValidator($validator);

		$this->assertEquals($post->getValidator(), $validator);
	}

	public function test_should_check_if_it_is_valid_when_it_is()
	{
		$post = new Post();
		$post->title = "Post Title";
		$post->content = "Post Content";

		$validator = Mockery::mock(Validator::class);
		$validator->shouldReceive('setRules')->with([
			'title' => 'required|max:255',
			'content' => 'required'
		]);
		$validator->shouldReceive('setData')->with([
			'title' => 'Post Title',
			'content' => 'Post Content'
			]);
		$validator->shouldReceive('fails')->andReturn(false);

		$post->setValidator($validator);

		$this->assertTrue($post->isValid());
	}

	public function test_should_check_if_it_is_invalid_when_it_is()
	{
		$post = new Post();
		$post->title = "Post Title";

		$messagebag = Mockery::mock(Illuminate\Support\MessageBag::class);

		$validator = Mockery::mock(Validator::class);
		$validator->shouldReceive('setRules')->with([
			'title' => 'required|max:255',
			'content' => 'required'
		]);
		$validator->shouldReceive('setData')->with([
			'title' => 'Post Title',
			]);

		$validator->shouldReceive('fails')->andReturn(true);
		$validator->shouldReceive('errors')->andReturn($messagebag);

		$post->setValidator($validator);

		$this->assertFalse($post->isValid());
		$this->assertEquals($messagebag, $post->errors);
	}

	public function test_check_if_a_post_can_be_persisted()
	{
		$post = Post::create([
			'title' => 'Post Title',
			'content' => 'Post Content'
			]);

		$this->assertEquals('Post Title', $post->title);
		$this->assertEquals('Post Content', $post->content);

		$post = Post::all()->first();

		$this->assertEquals('Post Title', $post->title);
	}

	public function test_can_validate_a_post()
	{
		$post = new Post();
		$post->title = "Post Title";
		$post->content = "Post Content";

		$factory = $this->app->make('Illuminate\Validation\Factory');

		$validator = $factory->make([],[]);

		$post->setValidator($validator);

		$this->assertTrue($post->isValid());

		$post->title = null;

		$this->assertFalse($post->isValid());
	}

	public function test_can_slug()
	{
		$post = Post::create([
			'title' => 'Post Title',
			'content' => 'Post Content'
			]);

		$this->assertEquals("post-title", $post->slug);

		$post = Post::create([
			'title' => 'Post Title',
			'content' => 'Post Content'
			]);

		$this->assertEquals("post-title-1", $post->slug);

		$post = Post::findBySlug('post-title');

		$this->assertEquals("post-title", $post->slug);
	}

	public function test_can_add_posts_to_categories()
	{
		$category = Category::create(['name' => 'Category', 'active' => true]);
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$post2 = Post::create(['title' => 'my post 2', 'content' => 'Content']);

		$post->categories()->save($category);
		$post2->categories()->save($category);

		$categories = Category::all();

		$this->assertCount(1, $categories);
		$this->assertEquals('Category', $post->categories->first()->name);
		$this->assertEquals('Category', $post2->categories->first()->name);

		$categoryRepository = new CategoryRepositoryEloquent();
		$postRepository = new PostRepositoryEloquent($categoryRepository);

		$posts = $postRepository->findByCategory(1);

		$this->assertCount(2, $posts);
		$this->assertEquals('my post 1', $posts[0]->title);
		$this->assertEquals('my post 2', $posts[1]->title);
	}

	public function test_can_create_comments()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$post->comments()->create(['content' => 'comment 1']);
		$post->comments()->create(['content' => 'comment 2']);
		$comments = Post::find(1)->comments;

		$this->assertCount(2, $comments);
		$this->assertEquals('comment 1', $comments[0]->content);
		$this->assertEquals('comment 2', $comments[1]->content);
	}

	public function test_can_soft_delete()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$post->delete();

		$this->assertTrue($post->trashed());
		$this->assertCount(0, Post::all());
	}

	public function test_can_get_rows_deleted()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$post2 = Post::create(['title' => 'my post 2', 'content' => 'Content']);
		$post->delete();
		$posts = Post::onlyTrashed()->get();

		$this->assertCount(1, $posts);	
	}

	public function test_can_get_rows_deleted_and_activated()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$post2 = Post::create(['title' => 'my post 2', 'content' => 'Content']);
		$post->delete();
		$posts = Post::withTrashed()->get();

		$this->assertCount(2, $posts);	
	}

	public function test_can_force_delete()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$post->forceDelete();
		$posts = Post::withTrashed()->get();

		$this->assertCount(0, $posts);	
	}

	public function test_can_restore_rows_trashed()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$post->delete();
		$post->restore();
		$posts = Post::all();

		$this->assertCount(1, $posts);	
	}
}