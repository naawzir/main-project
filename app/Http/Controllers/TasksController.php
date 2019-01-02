<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\StaffUser;

class TasksController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show($taskId)
    {
        $task = loadRecord(new Task, $taskId);

        $url_ext = $task->generateTaskUrl($task->target_type);
        $userStaff = new StaffUser;

        $data = [
            'task' => $task,
            'usersinrole' => $userStaff->getCurrentUsersInRole(),
            'url_ext' => $url_ext,
        ];

        return view('task.show', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $id = $request['id'];
        if ($task = Task::find($id)) {
            if ($request['nmethod'] === 'save') {
                $task->assigned_to = $request['assigned_to'];
                $task->date_due = Carbon::createFromFormat('d/m/Y', $request['date_due'])->timestamp;
                $task->task_notes = $request['task_notes'];
            } else {
                $task->follow_up = 2;
                $task->date_completed = time();
                $task->user_id_staff_completed = $user->id;
                $task->task_notes = $request['task_notes'];
            }

            $task->save();

            // redirect
            return redirect('/')->with('message', 'Task Updated.');
        }
        return redirect('/')->withErrors(['errors' => 'Could Not Update The Task.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        /** @var $user User */
        $user = Auth::user();
        Task::destroy($task);
        return redirect('/')->with('message', 'Task Deleted');
    }

    public function getTasks($type)
    {
        /** @var $user User */
        $user = Auth::user();
        $taskModel = new Task;
        $task = $taskModel->getTasksForUser($type);

        if (is_string($task)) {
            return response()->json([
                'success' => false,
                'message' => 'Task type: ' . $type . ' not found.',
            ]);
        }

        return Datatables::of($task)->make(true);
    }

    public function createTask(Request $request)
    {
        /** @var $user User */
        $user = Auth::user();
        $task = new Task;
        if ($task->createTask(
            $request->slug,
            $request->title,
            $request->target_id,
            $request->target_type,
            $request->type,
            $request->notes,
            $request->date_due,
            $request->assigned_to
        )) {
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Could not create ' . $request->title . ' task for user',
        ]);
    }
}
