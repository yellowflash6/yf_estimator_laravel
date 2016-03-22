<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use App\Http\Requests;
use App\Project;
use Excel;
use App\User;

class ProjectController extends Controller
{

    /*=========================================
    =            FIELD DEFINITIONS            =
    =========================================*/
    public $arr_field_definitions = [
        'sl_no'                     =>  'Sl. No.',
        'name'                      =>  'Name', 
        'description'               =>  'description', 
        'dev_code_estimate'         =>  'Dev. Code', 
        'dev_analysis_estimate'     =>  'Dev. Analysis',
        'dev_review_estimate'       =>  'Dev. Review', 
        'testing_estimate'          =>  'Testing', 
        'sw_config_estimate'        =>  'S/W Config.', 
        'documentation_estimate'    =>  'Documentation'
    ];
    /*=====  End of FIELD DEFINITIONS  ======*/
    

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
        return redirect('/projects');

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
    public function getProjects(Request $request)
    {
        $page_index             =   $request->get('current');
        $row_count              =   $request->get('rowCount');
        $str_search             =   $request->get('searchPhrase');
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
            $url = url('project-tasks', [$project->id]);
            $tasks = $project->tasks;
            $total = 0;
            foreach ($tasks as $task) 
            {
                # code...
                $total += $task->dev_code_estimate+$task->dev_analysis_estimate+$task->dev_review_estimate+$task->testing_estimate
                            +$task->sw_config_estimate+$task->documentation_estimate;
            }
            $temp_arr = array(
                               'id'                 => $project->id,
                               'name'               => $project->name,
                               'description'        => $project->description,
                               'tasks'              => "<a href='".$url."'>".sizeof($tasks)." Tasks</a>",
                               'total_man_hours'    => $total,
                               'total_man_days'     => $total/8
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

    

    /**
     * Export to Excel
     * This function will export all estimate values of a Project as an excel spreadsheet.
     * This is done using Laravel Excel module in Laravel (http://www.maatwebsite.nl/laravel-excel)
     */
    public function exportExcel($id)
    {   
        # code...
        $data       =   array();
        $project    =   Project::find($id);
        $tasks      =   $project->tasks;
        $cur_date   =   getdate();
        $str_date   =   $cur_date['mday'].'_'.$cur_date['month'].'_'.$cur_date['year'];
        $filename   =   snake_case($project->name)."_estimate_".$str_date;
        $sl_no      =   1;
        foreach ($tasks as $task) 
        {
            # code...
            $temp_array = array(
               $this->arr_field_definitions['sl_no']                      => $sl_no,
               $this->arr_field_definitions['name']                       => $task->name,
               $this->arr_field_definitions['dev_code_estimate']          => floatval($task->dev_code_estimate),
               $this->arr_field_definitions['dev_analysis_estimate']      => floatval($task->dev_analysis_estimate),
               $this->arr_field_definitions['dev_review_estimate']        => floatval($task->dev_review_estimate),
               $this->arr_field_definitions['testing_estimate']           => floatval($task->testing_estimate),
               $this->arr_field_definitions['sw_config_estimate']         => floatval($task->sw_config_estimate),
               $this->arr_field_definitions['documentation_estimate']     => floatval($task->documentation_estimate),
            );
            array_push($data, $temp_array);
            $sl_no++;
        }

        /*----------  Add Formulae  ----------*/
        $dev_column                         = $this->getColumnHeader('dev_code_estimate', $data);
        $dev_code_estimate_formulae         = "=SUM(".$dev_column."2:".$dev_column.(sizeof($data)+1).")";

        $dev_analysis_column                = $this->getColumnHeader('dev_analysis_estimate', $data);
        $dev_analysis_estimate_formulae     = "=SUM(".$dev_analysis_column."2:".$dev_analysis_column.(sizeof($data)+1).")";

        $dev_review_column                  = $this->getColumnHeader('dev_review_estimate', $data);
        $dev_review_estimate_formulae       = "=SUM(".$dev_review_column."2:".$dev_review_column.(sizeof($data)+1).")";

        $testing_column                     = $this->getColumnHeader('testing_estimate', $data);
        $testing_estimate_formulae          = "=SUM(".$testing_column."2:".$testing_column.(sizeof($data)+1).")";

        $sw_config_column                   = $this->getColumnHeader('sw_config_estimate', $data);
        $sw_config_estimate_formulae        = "=SUM(".$sw_config_column."2:".$sw_config_column.(sizeof($data)+1).")";

        $documentation_column               = $this->getColumnHeader('documentation_estimate', $data);
        $documentation_estimate_formulae    = "=SUM(".$documentation_column."2:".$documentation_column.(sizeof($data)+1).")";

        $temp_array = array(
           $this->arr_field_definitions['sl_no']                      => '',
           $this->arr_field_definitions['name']                       => 'Total Hours',
           $this->arr_field_definitions['dev_code_estimate']          => $dev_code_estimate_formulae,
           $this->arr_field_definitions['dev_analysis_estimate']      => $dev_analysis_estimate_formulae,
           $this->arr_field_definitions['dev_review_estimate']        => $dev_review_estimate_formulae,
           $this->arr_field_definitions['testing_estimate']           => $testing_estimate_formulae,
           $this->arr_field_definitions['sw_config_estimate']         => $sw_config_estimate_formulae,
           $this->arr_field_definitions['documentation_estimate']     => $documentation_estimate_formulae,
        );
        array_push($data, $temp_array);
        
        
        /*====================================
        =            EXCEL EXPORT            =
        ====================================*/
        Excel::create($filename, function($excel) use($data) {
            $excel->sheet('Estimate', function($sheet) use($data) {
                $sheet->fromArray($data, null, 'A1', true);
                // Set black background
                $sheet->row(1, function($row) {
                    // call cell manipulation methods
                    $row->setBackground('#DBDBDB')->setFontWeight('bold');

                })->freezeFirstRow();
                // Set auto size for sheet
                $sheet->setAutoSize(true);
                $sheet->row((sizeof($data)+1), function($row) {
                    // call cell manipulation methods
                    $row->setBackground('#DBDBDB')->setFontWeight('bold');
                });

            });
        })->download('xls');
        /*=====  End of EXCEL EXPORT  ======*/
        
    }

    /**
     * Get Excel Column Header
     * This function will return the excel column header for the spreadsheet to be exported.
     * This is computed based on the field name and data array with the help of which we will get the index of the key of field 
     * and its corresponding alphabet column name.
     */
    public function getColumnHeader($field_name='', $data)
    {
        # code...
        $alphabets = range('A', 'Z');
        $array_headings = array_keys($data[0]);
        $column_header = $alphabets[array_search($this->arr_field_definitions[$field_name], $array_headings)];
        return $column_header;
    }

}