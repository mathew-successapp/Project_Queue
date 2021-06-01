<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Project;
use App\Jobs\DeleteProjectJob;
use App\Jobs\UpdateProjectStatus;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use Carbon\Carbon;
use Auth;

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
        $projects = Project::where('user_id',$user_id)
                            ->withCount('tasks')
                            ->with('tasks')->get()->toArray();

        if(!empty($projects)){
            return new ProjectResource($projects);
        }
        return response()->json([
            'status' => false,
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
            return new ProjectResource($project);
        }
        return response()->json([
            'status' => false,
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
            return new ProjectResource($project);
        }
        return response()->json([
            'status' => false,
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
    public function update(UpdateProjectRequest $request, $id)
    { 
        $project = Project::findOrFail($id);
        $project->user_id = Auth::user()->id;
        $project->title = $request->title;
        $project->due_date = $request->due_date;
        $project->status = $request->status;

        if($project->save()){
            return new ProjectResource($project);
        }
        return response()->json([
            'status' => false,
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
            return new ProjectResource($project);
        }
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong'
        ]);
    }

    public function removeRecords()
    {
        return response()->json([
            'status' => DeleteProjectJob::dispatch(),
            'message' => 'Project deletion is started'
        ]);
    }

    public function updateStatus()
    {
        return response()->json([
            'status' => UpdateProjectStatus::dispatch(),
            'message' => 'Project status updation is started'
        ]);
    }
}
