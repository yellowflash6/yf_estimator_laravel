@extends('layouts.app')

@section('content')
<div class="container">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>Modify</strong>
    </div>
    <form class="form-signin" action="/edit-task/{{ $task->id }}" method="post">
    	  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    	  <!-- <input type="hidden" name="project_id" value={{ $task->project_id }} /> -->
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1"><i class="fa fa-cogs"></i></span>
          <input type="text" class="form-control" placeholder="Task Name (Max. 50 Characters)" name="name" data-validation-length="max50" data-validation="length required" 
            data-validation-help="Task Name" data-validation-error-msg="Task Name Requried! (Max. 50 Characters)" 
            value="{{ $task->name }}" autofocus />
        </div>
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon"><i class="fa fa-cogs"></i></span>
          <textarea class="form-control" rows="6" cols="40" placeholder="Description" name="description" data-validation="required">{{ $task->description }}</textarea>
        </div>
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
          <input type="text" class="form-control" placeholder="Dev. Code" name="dev_code_estimate" data-validation="required" 
            data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" 
            value = "{{ $task->dev_code_estimate }}" />
        </div>
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
          <input type="text" class="form-control" placeholder="Dev. Analysis" name="dev_analysis_estimate" data-validation="required" 
            data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" 
            value = "{{ $task->dev_analysis_estimate }}" />
        </div>
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
          <input type="text" class="form-control" placeholder="Dev. Review" name="dev_review_estimate" data-validation="required" 
            data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" 
            value = "{{ $task->dev_review_estimate }}" />
        </div>
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
          <input type="text" class="form-control" placeholder="Testing" name="testing_estimate" data-validation="required" 
            data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" 
            value = "{{ $task->testing_estimate }}" />
        </div>
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
          <input type="text" class="form-control" placeholder="SW/Data Config." name="sw_config_estimate" data-validation="required" 
            data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field"
            value = "{{ $task->sw_config_estimate }}" />
        </div>
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
          <input type="text" class="form-control" placeholder="Documentation" name="documentation_estimate" data-validation="required" 
            data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field"
            value = "{{ $task->documentation_estimate }}" />
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" onclick=location.assign('/project-tasks/{{ $task->project_id }}')>Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
  </div>
</div>
@endsection