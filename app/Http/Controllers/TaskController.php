<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Folder $folder)
    {       
        // ユーザーのフォルダを取得する
        $folders = Auth::user()->folders()->get();

        // 選ばれたフォルダを取得する
        // $current_folder = Folder::find($id);

        //選ばれたフォルダに紐づくタスクを取得する
        $tasks = $folder->tasks()->get();

        return view('tasks/index',[
            'folders' => $folders,
            'current_folder_id' => $folder->id,
            'tasks' => $tasks,
            ]);
    }

    public function showCreateForm(Folder $folder)
    {
        return view('tasks/create',[
            'folder_id' => $folder,
            ]);
    }

    public function create(Folder $folder,CreateTask $request)
    {
        // $current_folder = Folder::find($id);

        $task = new Task();
        $task->title = $request->title;
        $task->due_date = $request->due_date;

        $folder->tasks()->save($task);

        return redirect()->route('tasks.index',[
            'folder' => $folder->id,
        ]);
    }

    public function showEditForm(Folder $folder, int $task_id)
    {
        $task = Task::find($task_id);

        return view('tasks/edit',[
            'task' => $task,
        ]);
    }

    public function edit(Folder $folder, int $task_id, EditTask $request)
    {
        //1
        $task = Task::find($task_id);

        //2
        $task->title = $request->title;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->save();

        //3
        return redirect()->route('tasks.index',[
            'folder' => $task->folder_id,
        ]);
    }
}
