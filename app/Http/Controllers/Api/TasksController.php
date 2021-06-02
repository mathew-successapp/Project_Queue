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

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task = Tasks::with('project')->get()->toArray();

        if(!empty($task)){
            return new TaskResource($task);
        }
        return response()->json([
            'status' => false,
            'message' => 'No records found'
        ], 422);
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
        $task = Tasks::findOrFail($id); 
        $task->assignee_id = $request->assignee_id;

        if($task->save()){
            return new TaskResource($task);
        }
        return response()->json([
            'status' => false,
            'message' => 'Task creation Failed'
        ], 422);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request)
    {
        $task = new Tasks();
        $task->title = $request->title;

        if($task->save()){
            return new TaskResource($task);
        }
        return response()->json([
            'status' => false,
            'message' => 'Task creation Failed'
        ], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Tasks::where('id',$id)->first();
        if(!empty($task)){
            return new TaskResource($task);
        }
        return response()->json([
            'status' => false,
            'message' => 'No records found'
        ], 422);
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
        $task = Tasks::find($id); 
        $task->fill($request->validated());

        if($task){
            return new TaskResource($task);
        }
        return response()->json([
            'status' => false,
            'message' => 'Task update Failed'
        ], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
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
        ], 422);
    }
}
