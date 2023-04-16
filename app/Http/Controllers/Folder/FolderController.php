<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use App\Http\Resources\Folder\FolderResource;
use App\Models\Course;
use App\Models\Decision;
use App\Models\Folder;
use App\Models\Task;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function decisionShow(Course $course, Task $task, Decision $decision, Folder $folder)
    {
        $folder->loadMissing([
            'folders',
            'files'
        ]);

        return new FolderResource($folder);
    }

    public function taskShow(Course $course, Task $task, Folder $folder)
    {
        // dd($folder);
        $folder->loadMissing([
            'folders',
            'folder',
            'files'
        ]);


        return new FolderResource($folder);
    }

    public function update(Request $request, Folder $decisionFolder)
    {
        //
    }

    public function destroy(Folder $decisionFolder)
    {
        //
    }
}
