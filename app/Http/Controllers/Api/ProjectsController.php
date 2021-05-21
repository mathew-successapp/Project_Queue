<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Project;
use Webpatser\Uuid\Uuid;
use Auth;
use App\Jobs\DeleteProjectJob;
use App\Jobs\UpdateProjectStatus;
use Carbon\Carbon;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $projects = Project::where('user_id',$user_id)->get();

        if(!empty($projects)){
            return response()->json([
                'status' => true,
                'data' => $projects,
                'message' => ''
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'No records found'
        ]);
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
    public function store(ProjectStoreRequest $request) 
    {
        $project = new Project();
        $project->user_id = Auth::user()->id; 
        $project->title = $request->title;
        $project->due_date = $request->due_date;
        $project->status = $request->status;

        if($project->save()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Project saved successfully.'
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Project save Failed'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::user()->id;
        $project = Project::where('user_id',$user_id)->where('id',$id)->first();
        if(!empty($project)){
            return response()->json([
                'status' => true,
                'data' => $project,
                'message' => ''
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'No records found'
        ]);
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
    public function update(UpdateProjectRequest $request)
    {
        $project = Project::where('id',$request->id)->first(); //  dd($project);
        $project->user_id = Auth::user()->id;
        $project->title = $request->title;
        $project->due_date = $request->due_date;
        $project->status = $request->status;

        if($project->save()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Project saved successfully.'
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Project save Failed'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id = Auth::user()->id;
        $project = Project::where('user_id', $user_id)->where('id',$id)->first();
        
        if($project->delete()){ 
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Record deleted successfully'
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Something went wrong'
        ]);
    }

    public function removeRecords()
    {
        //sleep(20);
        if(DeleteProjectJob::dispatch()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Project deletion is started'
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Something went wrong'
        ]);
    }

    public function updateStatus()
    {
        if(UpdateProjectStatus::dispatch()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Project status updation is started'
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Something went wrong'
        ]);
    }
}
