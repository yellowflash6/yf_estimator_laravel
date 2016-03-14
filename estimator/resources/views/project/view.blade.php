@extends('layouts.app')

@section('content')

<div class="container">
	<div class="jumbotron">
		<h1>{{ $project->name }}</h1>
		<p>	{{ $project->description }}</p>
	</div>
</div>

@endsection