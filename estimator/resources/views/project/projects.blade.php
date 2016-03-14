@extends('layouts.app')

@section('content')

<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>List of Projects<strong>
		</div>

		<div class="table-responsive">
			<!-- <table class="table table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Total Tasks</th>
						<th>Total Man Hours</th>
						<th>Total Man Days</th>
					</tr>
				</thead>
			</table> -->
			<table id="grid-basic" class="table table-condensed table-hover table-striped">
				<thead>
					<tr>
						<th data-column-id="id" data-type="numeric">ID</th>
						<th data-column-id="title">Project</th>
						<th data-column-id="description">Description</th>
						<th data-column-id="description">Tasks</th>
						<th data-column-id="description">Total Man Hours</th>
						<th data-column-id="description">Total Man Days</th>
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
		    		id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
		    	};
		    },
		    url: "get_projects",
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
		    	//alert("You pressed edit on row: " + $(this).data("row-id"));
		    	location.assign("projects/"+$(this).data("row-id"));

		    }).end().find(".command-delete").on("click", function(e)
		    {
		    	var projectID = $(this).data("row-id");
		    	$.ajax({
				  url: "delete_project/",
				  data : {'project_id' : projectID},
				}).done(function( msg ) 
				{
					projectDeleteSuccess(msg);
				})
    		});
    	});
	    var projectDeleteSuccess = function(msg)
	    {
	    	console.log(msg);
			alert(msg);
	    }

    });
</script>
@endsection