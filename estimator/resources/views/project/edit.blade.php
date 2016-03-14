@extends('layouts.app')

@section('content')
	<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>Modify</strong>
		</div>
		<form class="form-signin" action="/edit-project/{{ $project->id }}" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1"><i class="fa fa-cog"></i></span>
				<input type="text" class="form-control" placeholder="Project Name (Max. 50 Characters)" name="project_name" 
						data-validation-length="max50" data-validation="length required" 
						data-validation-help="Project Name" data-validation-error-msg="Project Name Requried! (Max. 50 Characters)" 
						autofocus
						value = {{ $project->name }} />
			</div>
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon"><i class="fa fa-cog"></i></span>
				<textarea class="form-control" rows="6" cols="40" id="comment" placeholder="Description" name="project_description" data-validation="required">{{ $project->description }}</textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" onclick=location.assign('/projects')>Cancel</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
		</form>
	</div>
@endsection