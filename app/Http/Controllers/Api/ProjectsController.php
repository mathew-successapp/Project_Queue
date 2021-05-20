<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Project;
use Webpatser\Uuid\Uuid;
use Auth;
use App\Jobs\DeleteProjectJob;
use Carbon\Carbon;

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
        $user = User::find($user_id); 
        $projects = $user->allprojects;

        if(!empty($projects)){
            return response()->json([
                'status' => true,
                'data' => $projects,
                'message' => ''
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'No records found'
            ]);
        }
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
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $project = new Project();
        $project->user_id = $user_id;
        $project->project_title = $request->project_title;
        $project->due_date = $request->due_date;
        $project->status = $request->status;

        if($user->project()->save($project)){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Project saved successfully.'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Project save Failed'
            ]);
        }
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
    public function update(Request $request)
    {
        $project = Project::where('id',$request->id)->first(); //  dd($project);
        $project->user_id = Auth::user()->id;
        $project->project_title = $request->project_title;
        $project->due_date = $request->due_date;
        $project->status = $request->status;

        if($project->save()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Project saved successfully.'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Project save Failed'
            ]);
        }
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
        $user = User::find($user_id);
        
        if($user->allprojects()->where('id',$id)->delete()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Record deleted successfully'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function removeRecords()
    {
        $delete_projects = (new DeleteProjectJob())->delay(Carbon::now()->addSeconds(3));

        if(dispatch($delete_projects)){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Record deleted successfully'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Something went wrong'
            ]);
        }
    }
}
