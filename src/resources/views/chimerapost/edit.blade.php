@extends('layouts.app')

@section('content')

	<div class="container">
		<h3>Update Post</h3>

		{!! Form::open(['method' => 'post', 'route' => ['admin.posts.update', $post->id]]) !!}

		<div class="form-group">
			{!! Form::label('Title', 'Title:') !!}
			{!! Form::text('title', $post->title, ['class' => 'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('Content', 'Content:') !!}
			{!! Form::textarea('content', $post->content, ['class' => 'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Update', ['class' => 'form-control']) !!}
		</div>

		{!! Form::close() !!}

	</div>

@endsection