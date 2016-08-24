<?php

namespace Test\Post\Models;

use ChimeraRocks\Category\Models\Category;
use ChimeraRocks\Post\Models\Post;
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
		App::bind(
	    	\ChimeraRocks\Category\Models\Contracts\PostInterface::class, function () {
				return \ChimeraRocks\Post\Models\Post::class;
	    	}
		);
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
		$posts = Category::find(1)->posts;
		$this->assertCount(2, $posts);
		$this->assertEquals('my post 1', $posts[0]->title);
		$this->assertEquals('my post 2', $posts[1]->title);
	}
}