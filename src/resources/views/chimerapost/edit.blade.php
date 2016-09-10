@extends('layouts.app')

@section('content')
	<?php
		$textState = $post->state == $post::STATE_PUBLISH ? "Draft" : "Publish";
		$classState = $post->state == $post::STATE_PUBLISH ? "warning" : "success";
		$textLabel = $post->state == $post::STATE_PUBLISH ? "Publish" : "Draft";
		$classLabel = $post->state == $post::STATE_PUBLISH ? "success" : "warning";
		$state = $post->state == $post::STATE_PUBLISH ? $post::STATE_PUBLISH : $post::STATE_DRAFT;
	?>

	<div class="container">
		<h3>Update Post</h3>
		<h3><span class="label label-{{$classLabel}}"></span>{{ $textLabel }}</h3>
		{!! Form::open(['method' => 'post', 'route' => ['admin.posts.update', $post->id]]) !!}

		<div class="form-group">
			{!! Form::label('Title', 'Title:') !!}
			{!! Form::text('title', $post->title, ['class' => 'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('Content', 'Content:') !!}
			{-- Form::textarea('content', $post->content, ['class' => 'form-control']) --}
			<textarea name="content" class="tinymce">{!! old('content') or $post->content !!}</textarea>
			@include('tinymce::tpl')
		</div>

		<div class="form-group">
			{!! Form::submit('Update', ['class' => 'form-control']) !!}
		</div>

		{!! Form::close() !!}

		@can('publish_post')
		{!! Form::model(['method' => 'patch', 'route' => ['admin.posts.update_state', $post->id]]) !!}
			<div class="form-group">
				{!! Form::hidden('state', $state) !!}
				{!! Form::submit($textState, ['class' => 'btn btn-lg btn-block btn-' . $classState]) !!}
			</div>
		{!! Form::close() !!}
		@endcan
	</div>

@endsection