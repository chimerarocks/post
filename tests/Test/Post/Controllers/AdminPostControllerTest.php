<?php

namespace Test\Post\Controllers;

use ChimeraRocks\Post\Controllers\AdminPostController;
use ChimeraRocks\Post\Controllers\Controller;
use ChimeraRocks\Post\Repositories\PostRepositoryEloquent;
use Illuminate\Contracts\Routing\ResponseFactory;
use Mockery;
use Test\AbstactTestCase;

class AdminPostControllerTest extends AbstactTestCase
{
	public function test_should_extends_from_controller()
	{
		$postRepository = Mockery::mock(PostRepositoryEloquent::class);
		$response = Mockery::mock(ResponseFactory::class);

		$controller = new AdminPostController($postRepository, $response);

		$this->assertInstanceOf(Controller::class, $controller);
	}

	public function test_controller_should_run_index_method_and_return_correct_arguments()
	{
		$postRepository = Mockery::mock(PostRepositoryEloquent::class);
		$response = Mockery::mock(ResponseFactory::class);
		$html = Mockery::mock();

		$controller = new AdminPostController($postRepository, $response);

		$postsResult = ['Post1', 'Post2'];
		$postRepository->shouldReceive('all')->andReturn($postsResult);
		$response->shouldReceive('view')
		    ->with('chimerapost::index', ['posts' => $postsResult])
		    ->andReturn($html);

		$this->assertEquals($controller->index(), $html);
	}
}