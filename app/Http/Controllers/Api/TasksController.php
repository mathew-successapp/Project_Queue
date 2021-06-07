<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Tasks;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Gate;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::allows('isSEM') || Gate::allows('isClient')){
            $task = Tasks::with('project')->get();

            if(!empty($task)){
                return TaskResource::collection($task);
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

    public function assign(AssignTaskRequest $request, $id)
    {
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
            $task = Tasks::find($id); 
            if(!empty($task)){
                $task->assignee_id = $request->assignee_id;
                $task->save();
                return new TaskResource($task);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request)
    {
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
            $task = new Tasks();
            $task->title = $request->title;
            $task->project_id = $request->project_id;
            $task->description = $request->description;
            $task->due_date = $request->due_date;
            $task->assignee_id = $request->assignee_id;

            if($task->save()){
                return new TaskResource($task);
            }
            return response()->json([
                'status' => false,
                'message' => 'Task creation Failed'
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
        $task = Tasks::where('id',$id)->with('project')->first(); 
        if(!empty($task)){
            return new TaskResource($task);
        }
        return response()->json([
            'status' => false,
            'message' => 'No records found'
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
    public function update(TaskUpdateRequest $request, $id)
    {
        if(Gate::allows('isSEM') || Gate::allows('isSE')){
            $task = Tasks::find($id); 
            if(!empty($task)){ 
                $task->update($request->validated());
                return new TaskResource($task);
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
            $task = Tasks::find($id);

            if(!empty($task)){
                $task->delete();
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
}
