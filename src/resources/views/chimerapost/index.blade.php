@extends('layouts.app')

@section('content')

	<div class="container">
		<h3>Posts</h3>
		<a href="{{route('admin.posts.create')}}">Create</a>
		<br><br>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Title</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@forelse($posts as $post)
				<tr>
					<td>{{$post->id}}</td>
					<td>{{$post->title}}</td>
					<td><a href="{{route('admin.posts.edit', ['id' => $post->id])}}">Update</a></td>
				@empty
					<td colspan="4"> Nenhum post registrad </td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

@endsection