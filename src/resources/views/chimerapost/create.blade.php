@extends('layouts.app')

@section('content')

	<div class="container">
		<h3>Create Post</h3>

		{!! Form::open(['method' => 'post', 'route' => ['admin.posts.store']]) !!}

		<div class="form-group">
			{!! Form::label('Title', 'Title:') !!}
			{!! Form::text('title', null, ['class' => 'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('Content', 'Content:') !!}
			{-- Form::textarea('content', null, ['class' => 'form-control']) --}
			<textarea name="content" class="tinymce"></textarea>
			@include('tinymce::tpl')
		</div>

		<div class="form-group">
			{!! Form::submit('Create Post', ['class' => 'form-control']) !!}
		</div>

		{!! Form::close() !!}

	</div>

@endsection