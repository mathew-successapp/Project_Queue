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
use Illuminate\Support\Facades\Gate;
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
                            ->with('tasks')->get(); //dd($projects->toArray());
        
        if(Gate::allows('isSEM') || Gate::allows('isClient')){
            if($projects){
                return ProjectResource::collection($projects);
            }    
            return response()->json([
                'status' => false,
                'message' => 'No records found'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Permission denied !'
        ], 200);
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
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
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
            ], 422);
        }
        return response()->json([
            'status' => false,
            'message' => 'Permission denied !'
        ], 200);   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
            $user_id = Auth::user()->id;
            $project = Project::with('tasks')->where('user_id',$user_id)->where('id',$id)->first();
            if(!empty($project)){
                return new ProjectResource($project);
            }
            return response()->json([
                'status' => false,
                'message' => 'No records found'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Permission denied !'
        ], 200);
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
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
            $project = Project::findOrFail($id);
            if(!empty($project)){
                $project->update($request->validated());
                return new ProjectResource($project);
            }

            return response()->json([
                'status' => false,
                'message' => 'No records found'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Permission denied !'
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
            $user_id = Auth::user()->id;
            $project = Project::find($id); 
            
            if(!empty($project)){
                $project->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Record deleted successfully'
                ], 200);
            }
            return response()->json([
                'status' => false,
                'message' => 'No record found'
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Permission denied !'
        ], 200);

    }

    public function removeRecords()
    {
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
            return response()->json([
                'status' => DeleteProjectJob::dispatch(),
                'message' => 'Project deletion is started'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Permission denied !'
        ], 200);
    }

    public function updateStatus()
    {
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
            return response()->json([
                'status' => UpdateProjectStatus::dispatch(),
                'message' => 'Project status updation is started'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Permission denied !'
        ], 200);
    }
}
