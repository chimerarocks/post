<?php

namespace Test\Post\Models;

use ChimeraRocks\Post\Models\Comment;
use ChimeraRocks\Post\Models\Post;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use Mockery;
use Test\AbstactTestCase;

class CommentTest extends AbstactTestCase
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
		$comment = new Comment();
		$validator = Mockery::mock(Validator::class);
		$comment->setValidator($validator);

		$this->assertEquals($comment->getValidator(), $validator);
	}

	public function test_should_check_if_it_is_valid_when_it_is()
	{
		$comment = new Comment();
		$comment->content = "Comment Content";

		$validator = Mockery::mock(Validator::class);
		$validator->shouldReceive('setRules')->with([
			'content' => 'required'
		]);
		$validator->shouldReceive('setData')->with([
			'content' => 'Comment Content'
			]);
		$validator->shouldReceive('fails')->andReturn(false);

		$comment->setValidator($validator);

		$this->assertTrue($comment->isValid());
	}

	public function test_should_check_if_it_is_invalid_when_it_is()
	{
		$comment = new Comment();
		$comment->content = "Comment Content";

		$messagebag = Mockery::mock(Illuminate\Support\MessageBag::class);

		$validator = Mockery::mock(Validator::class);
		$validator->shouldReceive('setRules')->with([
			'content' => 'required'
		]);
		$validator->shouldReceive('setData')->with([
			'content' => 'Comment Content'
			]);

		$validator->shouldReceive('fails')->andReturn(true);
		$validator->shouldReceive('errors')->andReturn($messagebag);

		$comment->setValidator($validator);

		$this->assertFalse($comment->isValid());
		$this->assertEquals($messagebag, $comment->errors);
	}

	public function test_check_if_a_comment_can_be_persisted()
	{
		$post = Post::create([
			'title' => 'my post 1', 
			'content' => 'Content'
			]);
		$comment = Comment::create([
			'content' => 'Comment Content',
			'post_id' => $post->id
			]);

		$this->assertEquals('Comment Content', $comment->content);

		$comment = Comment::all()->first();
		$this->assertEquals('Comment Content', $comment->content);

		$post = Comment::find(1)->post;
		$this->assertEquals('my post 1', $post->title);
	}

	public function test_can_validate_a_comment()
	{
		$comment = new Comment();
		$comment->content = "Comment Content";

		$factory = $this->app->make('Illuminate\Validation\Factory');
		$validator = $factory->make([],[]);

		$comment->setValidator($validator);

		$this->assertTrue($comment->isValid());
		$comment->content = null;
		$this->assertFalse($comment->isValid());
	}

	public function test_can_delete_all_from_relationship()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$comment = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$comment2 = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$post->comments()->delete();

		$this->assertCount(0, $post->comments);	

		$comment = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$comment2 = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$post->comments()->forceDelete();

		$this->assertCount(0, $post->comments);	
	}

	public function test_can_restore_all_from_relationship()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$comment = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$comment2 = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$post->comments()->delete();
		$post->comments()->restore();

		$this->assertCount(2, $post->comments);	
	}

	public function test_cannot_restore_all_from_relationship_when_forcedeleted()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$comment = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$comment2 = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$post->comments()->forceDelete();
		$post->comments()->restore();

		$this->assertCount(0, $post->comments);	
	}

	public function test_can_find_the_model_deleted_from_relationship()
	{
		$post = Post::create(['title' => 'my post 1', 'content' => 'Content']);
		$comment = Comment::create(['content' => 'Comment Content','post_id' => $post->id]);
		$post->delete();
		$comment = Comment::find(1);
		$this->assertEquals('my post 1', $comment->post->title);
	}
}