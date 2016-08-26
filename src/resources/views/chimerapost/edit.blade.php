@extends('layouts.app')

@section('content')

	<div class="container">
		<h3>Update Post</h3>

		{!! Form::open(['method' => 'post', 'route' => ['admin.posts.update', $post->id]]) !!}

		<div class="form-group">
			{!! Form::label('Parent', 'Parent:') !!}
			<select name="parent_id" class="form-control">
				<option value="">-None-</option>
				@foreach($posts as $post)
					<option value="{{$post->id}}">{{$post->name}}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			{!! Form::label('Title', 'Title:') !!}
			{!! Form::text('title', null, ['class' => 'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::label('Content', 'Content:') !!}
			{!! Form::textarea('content', null, ['class' => 'form-control']) !!}
		</div>

		<div class="form-group">
			{!! Form::submit('Update', ['class' => 'form-control']) !!}
		</div>

		{!! Form::close() !!}

	</div>

@endsection