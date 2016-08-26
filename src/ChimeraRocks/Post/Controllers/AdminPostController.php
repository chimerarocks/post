<?php

namespace ChimeraRocks\Post\Controllers;

use ChimeraRocks\Post\Repositories\PostRepositoryInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
	private $postRepository;
	private $response;

	public function __construct(PostRepositoryInterface $postRepository, ResponseFactory $response)
	{
		$this->postRepository = $postRepository;
		$this->response = $response;
	}

	public function index()
	{
		return $this->response->view('chimerapost::index', [
			'posts' => $this->postRepository->all()
		]);
	}

	public function create()
	{
		$posts = $this->postRepository->all();
		return $this->response->view('chimerapost::create', compact('posts'));
	}

	public function store(Request $request)
	{
		$this->postRepository->create($request->all());
		return redirect()->route('admin.posts.index');
	}

	public function edit($id)
	{
		$post = $this->postRepository->find($id);
		return $this->response->view('chimerapost::edit', compact('post'));
	}

	public function update(Request $request, $id)
	{
		$post = $this->postRepository->update($data, $id);

		return redirect()->route('admin.posts.index');
	}
}