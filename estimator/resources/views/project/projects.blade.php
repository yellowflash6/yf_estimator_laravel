@extends('layouts.app')

@section('content')

<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong style="font-size:16px;">List of Projects</strong>
			<buttton class="btn btn-success" style="margin-left:80%;" data-toggle="modal" data-target="#modalAddProject" title="Create new Project!">
				<i class="fa fa-cog"></i>
				<strong>New Project</strong>
			</button>

		</div>
		<input type="hidden" id="csrfToken" name="_token" value="{{ csrf_token() }}">
		<div class="table-responsive">
			<table id="grid-basic" class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th data-column-id="id" data-type="numeric">ID</th>
						<th data-column-id="name">Project</th>
						<th data-column-id="description">Description</th>
						<th data-column-id="tasks">Tasks</th>
						<th data-column-id="total_man_hours">Total Man Hours</th>
						<th data-column-id="total_man_days">Total Man Days</th>
						<th data-column-id="status" data-order="desc">Status</th>
						<th data-column-id="commands" data-formatter="commands" data-sortable="false">Options</th>
					</tr>
				</thead>
			</table>  
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
    		var grid = $("#grid-basic").bootgrid({
		    ajax: true,
		    post: function ()
		    {
		    	return {
		    		id: "b0df282a-0d67-40e5-8558-c9e93b7befed",
		    		_token : "{{ csrf_token() }}",
		    	};
		    },
		    url: "/get-projects",
		    formatters: {
		    	"commands": function(column, row)
			    {
			    	return "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id + "\" title=\"View\"><span class=\"fa fa-pencil\"></span></button> " +
			    	"<button type=\"button\" class=\"btn btn-xs btn-default command-excel\" data-row-id=\"" + row.id + "\" title=\"Export as Excel\"><span class=\"fa fa-download\"></span></button>"
			    }
		    }
	    }).on("loaded.rs.jquery.bootgrid", function()
	    {
	    	console.log("on loaded;");
		    /* Executes after data is loaded and rendered */
		    grid.find(".command-edit").on("click", function(e)
		    {
		    	location.assign("/projects/"+$(this).data("row-id"));

		    }).end().find(".command-excel").on("click", function(e)
		    {
		    	/*var projectID = $(this).data("row-id");
		    	$.ajax({
				  url: "delete_project/",
				  data : {'project_id' : projectID},
				}).done(function( msg ) 
				{
					projectDeleteSuccess(msg);
				})*/
		    	location.assign("/test/"+$(this).data("row-id"));
		    	//alert("Delete not implemented now! Sorry.");
    		});
    	});
	    var projectDeleteSuccess = function(msg)
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
<!-- ==================================================== Start Add Project Modal ========================================================================================== -->
<div class="modal fade" id="modalAddProject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Project</h4>
      </div>
      <div class="modal-body">
      <form class="form-signin" action="/new-project" method="post">
      	  <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-cog"></i></span>
            <input type="text" class="form-control" placeholder="Project Name (Max. 50 Characters)" name="project_name" data-validation-length="max50" data-validation="length required" 
              data-validation-help="Project Name" data-validation-error-msg="Project Name Requried! (Max. 50 Characters)" autofocus>
          </div>
          <div class="input-group">
            <span class="input-group-addon" id="basic-addon"><i class="fa fa-cog"></i></span>
            <textarea class="form-control" rows="6" cols="40" id="comment" placeholder="Description" name="project_description" data-validation="required"></textarea>
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
<!-- ==================================================== End Add Project Modal ============================================================================================ -->
@endsection