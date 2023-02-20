<?php

namespace App\Http\Controllers\Course\Task\File;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Decision;
use App\Models\File;
use App\Models\Task;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
    }

    public function storeDecision($request, Course $course, Task $task)
    {
    }

    public function storeTask($request, Course $course, Task $task, Decision $decision)
    {
    }

    public function show(File $file)
    {
        //
    }

    public function update(Request $request, File $file)
    {
        //
    }

    public function destroy(File $file)
    {
        //
    }
}
