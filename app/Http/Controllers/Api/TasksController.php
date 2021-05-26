<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Tasks;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\CreateTaskRequest;

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
            return response()->json([
                'status' => true,
                'data' => $task,
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
    public function create(CreateTaskRequest $request)
    {
        $task = new Tasks();
        $task->title = $request->title;

        if($task->save()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Task created successfully.'
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Task creation Failed'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request)
    {
        $task = Tasks::findOrFail($request->id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->assignee_id = $request->assignee_id;
        $task->project_id = $request->project_id;

        if($task->save()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Task assigned successfully.'
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Task assign Failed'
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
        $task = Tasks::where('id',$id)->first();
        if(!empty($task)){
            return response()->json([
                'status' => true,
                'data' => $task,
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
    public function update(TaskUpdateRequest $request)
    {
        $task = Tasks::find($request->id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->assignee_id = $request->assignee_id;
        $task->project_id = $request->project_id;

        if($task->save()){
            return response()->json([
                'status' => true,
                'data' => [],
                'message' => 'Task saved successfully.'
            ]);
        }
        return response()->json([
            'status' => false,
            'data' => [],
            'message' => 'Task assign Failed'
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
        $task = Tasks::findOrFail($id);
        
        if($task->delete()){ 
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
}
