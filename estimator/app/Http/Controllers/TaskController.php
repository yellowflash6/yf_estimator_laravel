<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Project;
use App\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
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
        $task = new Task();
        $task->name = $request->get('name');
        $task->description = $request->get('description');
        $task->project_id = $request->get('project_id');
        $task->dev_code_estimate = $request->get('dev_code_estimate');
        $task->dev_analysis_estimate = $request->get('dev_analysis_estimate');
        $task->dev_review_estimate = $request->get('dev_review_estimate');
        $task->testing_estimate = $request->get('testing_estimate');
        $task->sw_config_estimate = $request->get('sw_config_estimate');
        $task->documentation_estimate = $request->get('documentation_estimate');
        $task->save();
        return redirect('project-tasks/'.$request->project_id);

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
        $task = Task::find($id);
        
        return view('task/edit')->with('task', $task);
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
        $task                =   Task::find($id);
        $task->name          =   $request->get('name');
        $task->description   =   $request->get('description');
        $task->dev_code_estimate = $request->get('dev_code_estimate');
        $task->dev_analysis_estimate = $request->get('dev_analysis_estimate');
        $task->dev_review_estimate = $request->get('dev_review_estimate');
        $task->testing_estimate = $request->get('testing_estimate');
        $task->sw_config_estimate = $request->get('sw_config_estimate');
        $task->documentation_estimate = $request->get('documentation_estimate');
        $task->save();
        return redirect('project-tasks/'.$request->project_id);
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
     * Return JSON response of Tasks data to fill in Bootstrap JQGrid
     * 
     * @param null
     * @return JSON
     */
    public function getTasks(Request $request)
    {
        $page_index             =   1;//$request->get('current');
        $row_count              =   10;//$request->get('rowCount');
        $str_search             =   "";//$request->get('searchPhrase');
        $project_id             =   $request->get('project_id');
        $arr_tasks              =   Task::where('project_id', $project_id)->get();
        
        //A Counter to keep track of array index.
        $ctr                    =   0;
        
        //Font Awesome HTML for Tick and Cross symbols to represent Active and Inactive status.
        $active_status_data     =   '<i class="fa fa-check text-success"></i>';
        $inactive_status_data   =   '<i class="fa fa-times text-danger"></i>';

        $arr_status_data        =   array();

        //Go through the Task data elements and replace status with Font Awesome HTML.
        //The new array is used as Tasks data.
        foreach ($arr_tasks as $task)
        {
            $total = $task->dev_code_estimate+$task->dev_analysis_estimate+$task->dev_review_estimate+$task->testing_estimate
                     +$task->sw_config_estimate+$task->documentation_estimate;
            $temp_arr = array(
                               'id'                         => $task->id,
                               'name'                       => $task->name,
                               'description'                => $task->description,
                               'dev_code_estimate'          => $task->dev_code_estimate,
                               'dev_analysis_estimate'      => $task->dev_analysis_estimate,
                               'dev_review_estimate'        => $task->dev_review_estimate,
                               'testing_estimate'           => $task->testing_estimate,
                               'sw_config_estimate'         => $task->sw_config_estimate,
                               'documentation_estimate'     => $task->documentation_estimate,
                               'total_estimate'             => $total,
                            );
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


        $tasks               = array();
        $tasks['current']    = intval($page_index);
        $tasks['rowCount']   = $row_count;
        $tasks['rows']       = $arr_rows_data;
        $tasks['total']      = sizeof($arr_tasks);
        echo json_encode($tasks);
    }
    

    /**
     * Show Project Tasks View
     * 
     *
     */
    public function showTasks($id)
    {
        $project = Project::find($id);

        return view('task/tasks')->with('project', $project);
    }
}
