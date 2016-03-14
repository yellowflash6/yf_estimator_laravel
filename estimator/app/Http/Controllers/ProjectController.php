<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use App\Http\Requests;
use App\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('project/projects');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $project                =   new Project();
        $project->name          =   $request->get('project_name');
        $project->description   =   $request->get('project_description');
        $project->status        =   1;
        $project->save();
        return view('project/projects');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $project = Project::find($id);
        
        return view('project/edit')->with('project', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $project                =   Project::find($id);
        $project->name          =   $request->get('project_name');
        $project->description   =   $request->get('project_description');
        $project->save();
        return redirect('/projects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Return JSON response of Projects data to fill in Bootstrap JQGrid
     * 
     * @param null
     * @return JSON
     */
    public function get_projects()
    {
        $page_index             =   1;//$request->get('current');
        $row_count              =   10;//$request->get('rowCount');
        $str_search             =   "";//$request->get('searchPhrase');
        $arr_projects           =   Project::where('status', 1)->get();
        
        //A Counter to keep track of array index.
        $ctr                    =   0;
        
        //Font Awesome HTML for Tick and Cross symbols to represent Active and Inactive status.
        $active_status_data     =   '<i class="fa fa-check text-success"></i>';
        $inactive_status_data   =   '<i class="fa fa-times text-danger"></i>';

        $arr_status_data        =   array();

        //Go through the Project data elements and replace status with Font Awesome HTML.
        //The new array is used as Project data.
        foreach ($arr_projects as $project)
        {
            $temp_arr = array(
                               'id'                 => $project->id,
                               'name'               => $project->name,
                               'description'        => $project->description,
                               'tasks'              => "<a href='#'># of Tasks</a>",
                               'total_man_hours'    => 0,
                               'total_man_days'     => 0
                            );
            
            if ($project->status)
            {
                $temp_arr['status'] = $active_status_data;
            }
            else
            {
                $temp_arr['status'] = $inactive_status_data;
            }

            array_push($arr_status_data, $temp_arr);

            $ctr++;
        }

        $arr_rows_data = array();

        /*
         * When any pagination option is selected, a POST request is sent here which will have the following
         * current (Current Page number), rowCount (No. of records to be shown selected), searchPhrase (Search string, if any).
         * If rowCount is -1, it means All Records option is selected in Grid. Else, rowCount will be 10, 25 or 50.
         */

        //Show the number of records as per the rowCount specified.
        if($row_count != -1)
        {
            $arr_data_chunk = array();
            $arr_data_chunk = array_chunk($arr_status_data, $row_count);
            $arr_rows_data  = $arr_data_chunk[$page_index-1];
        }
        //Show All records.
        else
        {
            $arr_rows_data = $arr_status_data;
        }


        $projects               = array();
        $projects['current']    = intval($page_index);
        $projects['rowCount']   = $row_count;
        $projects['rows']       = $arr_rows_data;
        $projects['total']      = sizeof($arr_projects);
        echo json_encode($projects);
    }

}
