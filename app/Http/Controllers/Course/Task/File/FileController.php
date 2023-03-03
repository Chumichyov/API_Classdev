<?php

namespace App\Http\Controllers\Course\Task\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileRequest;
use App\Http\Resources\File\FileResource;
use App\Models\Course;
use App\Models\FileExtension;
use App\Models\Task;
use App\Models\TaskFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Course $course, Task $task)
    {
        return FileResource::collection($task->files);
    }

    public function store(FileRequest $request, Course $course, Task $task)
    {
        try {
            $credentials = $request->validated();

            $path = 'courses/course_' . $course->id . '/files/task_' . $task->id;

            foreach ($credentials['files'] as $file) {
                $extension = $file->getClientOriginalExtension();

                $fileName = md5(microtime()) . '.' . $extension;
                $file->storeAs('public/' . $path, $fileName);

                TaskFile::create([
                    'task_id' => $task->id,
                    'user_id' => auth()->user()->id,
                    'file_extension_id' => !is_null(FileExtension::where('extension', $extension)->first()) ? FileExtension::where('extension', $extension)->first()->id : null,
                    'original_name' => $file->getClientOriginalName(),
                    'file_name' => $fileName,
                    'file_path' => '/storage/' . $path . '/' . $fileName,
                ]);
            }

            return response(['success_message' => 'Файлы успешно загружены.']);
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку.']);
        }
    }

    public function show(TaskFile $file)
    {
        //
    }

    public function update(Request $request, TaskFile $file)
    {
        //
    }

    public function destroy(Course $course, Task $task, TaskFile $file)
    {
        $file->delete();

        Storage::disk('public')->delete(substr($file->file_path, 9));

        return response(['success_message' => 'Файл успешно удален.']);
    }
}
