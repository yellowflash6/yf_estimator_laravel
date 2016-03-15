@extends('layouts.app')

@section('content')

<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong style="font-size:16px;">Tasks for {{ $project->name }}</strong>
			<buttton class="btn btn-success" style="margin-left:1%;" data-toggle="modal" data-target="#modalAddTask" title="Create new Task!">
				<i class="fa fa-cogs"></i>
				<strong>New Task</strong>
			</button>

		</div>
		<input type="hidden" id="csrfToken" name="_token" value="{{ csrf_token() }}">
		<div class="table-responsive">
			<table id="grid-basic" class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th data-column-id="id" data-type="numeric">ID</th>
						<th data-column-id="name">Name</th>
						<th data-column-id="description">Description</th>
						<th data-column-id="dev_code_estimate">Dev Code</th>
						<th data-column-id="dev_analysis_estimate">Dev Analysis</th>
						<th data-column-id="dev_review_estimate">Dev Review</th>
						<th data-column-id="documentation_estimate">Documentation</th>
						<th data-column-id="sw_config_estimate">SW/Data Config.</th>
						<th data-column-id="testing_estimate">Testing</th>
						<th data-column-id="total_estimate">Total</th>
						<th data-column-id="commands" data-formatter="commands" data-sortable="false">Options</th>
					</tr>
				</thead>
			</table>  
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
     
    /*var globalCSRF = {{ json_encode(array("_token" => csrf_token())) }};  
	var token = globalCSRF._token;*/
    var grid = $("#grid-basic").bootgrid({
		    ajax: true,
		    post: function ()
		    {
		    	return {
		    		id: "b0df282a-0d67-40e5-8558-c9e93b7befed",
		    		_token : "{{ csrf_token() }}",
		    		project_id : "{{ $project->id }}"
		    	};
		    },
		    url: "/get-project-tasks",
		    formatters: {
		    	"commands": function(column, row)
			    {
			    	return "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id + "\" title=\"View\"><span class=\"fa fa-pencil\"></span></button> " +
			    	"<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id + "\" title=\"Delete\"><span class=\"fa fa-trash-o\"></span></button>";
			    }
		    }
	    }).on("loaded.rs.jquery.bootgrid", function()
	    {
	    	console.log("on loaded;");
		    /* Executes after data is loaded and rendered */
		    grid.find(".command-edit").on("click", function(e)
		    {
		    	location.assign("/tasks/"+$(this).data("row-id"));

		    }).end().find(".command-delete").on("click", function(e)
		    {
		    	/*var projectID = $(this).data("row-id");
		    	$.ajax({
				  url: "delete_project/",
				  data : {'project_id' : projectID},
				}).done(function( msg ) 
				{
					projectDeleteSuccess(msg);
				})*/
		    	alert("Delete not implemented now! Sorry.");
    		});
    	});
	    var taskDeleteSuccess = function(msg)
	    {
	    	console.log(msg);
			alert(msg);
	    }

    });
</script>
<script>   
  $.validate({
      modules : 'security'
    });
</script>
<!-- ==================================================== Start Add Task Modal ========================================================================================== -->
<div class="modal fade" id="modalAddTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Task</h4>
      </div>
      <div class="modal-body">
      <form class="form-signin" action="/new-task" method="post">
      	  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
      	  <input type="hidden" name="project_id" value={{ $project->id }} />
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-cogs"></i></span>
            <input type="text" class="form-control" placeholder="Task Name (Max. 50 Characters)" name="name" data-validation-length="max50" data-validation="length required" 
              data-validation-help="Task Name" data-validation-error-msg="Task Name Requried! (Max. 50 Characters)" autofocus>
          </div>
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon"><i class="fa fa-cogs"></i></span>
            <textarea class="form-control" rows="6" cols="40" placeholder="Description" name="description" data-validation="required"></textarea>
          </div>
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
            <input type="text" class="form-control" placeholder="Dev. Code" name="dev_code_estimate" data-validation="required" 
              data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" />
          </div>
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
            <input type="text" class="form-control" placeholder="Dev. Analysis" name="dev_analysis_estimate" data-validation="required" 
              data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" />
          </div>
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
            <input type="text" class="form-control" placeholder="Dev. Review" name="dev_review_estimate" data-validation="required" 
              data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" />
          </div>
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
            <input type="text" class="form-control" placeholder="Testing" name="testing_estimate" data-validation="required" 
              data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" />
          </div>
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
            <input type="text" class="form-control" placeholder="SW/Data Config." name="sw_config_estimate" data-validation="required" 
              data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" />
          </div>
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-clock-o"></i></span>
            <input type="text" class="form-control" placeholder="Documentation" name="documentation_estimate" data-validation="required" 
              data-validation-help="Input values in hours." data-validation-error-msg="Mandatory field" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
      </form>
      </div>
    </div>
  </div>
</div>
<!-- ==================================================== End Add Task Modal ============================================================================================ -->

@endsection