<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use App\Http\Requests\Folder\StoreRequest;
use App\Http\Resources\Folder\FolderResource;
use App\Models\Course;
use Exception;
use App\Models\Decision;
use App\Models\Folder;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

class FolderController extends Controller
{
    public function index()
    {
        //
    }

    public function taskStore(StoreRequest $request, Course $course, Task $task)
    {
        $credentials = $request->validated();

        if (isset($credentials['folder'])) {
            $folder = Folder::find($credentials['folder']);
        } else if (Folder::where('folder_id', null)->where('task_id', $task->id)->count() != 0) {
            $folder = Folder::where('folder_id', null)->where('task_id', $task->id)->first();
        } else {
            $folder = Folder::create([
                'task_id' => $task->id,
                'user_id' => auth()->user()->id,
                'folder_id' => null,
                'original_name' => 'files',
                'folder_path' => '/storage/' . 'courses/course_' . $course->id . '/task_' . $task->id . '/files'
            ]);
        }

        $path = $folder->folder_path . '/' . $credentials['title'];

        if (Storage::disk('public')->exists(mb_substr($path, 9))) {
            $i = 1;

            do {
                $title = $credentials['title'] . '_' . $i;
                $i++;
            } while (Folder::where('task_id', $task->id)->where('folder_id', $folder->id)->where('original_name', $title)->count() != 0);

            $path = $folder->folder_path . '/' . $title;
            $credentials['title'] = $title;
        }

        Storage::disk('public')->makeDirectory(mb_substr($path, 9));

        Folder::create([
            'task_id' => $task->id,
            'user_id' => auth()->user()->id,
            'folder_id' => $folder->id,
            'original_name' => $credentials['title'],
            'folder_path' => $path,
        ]);


        return response(['data' => [
            'main' => $folder
        ]]);
    }

    public function decisionStore(StoreRequest $request, Course $course, Task $task, Decision $decision)
    {
        $credentials = $request->validated();

        if (isset($credentials['folder'])) {
            $folder = Folder::find($credentials['folder']);
        } else if (Folder::where('folder_id', null)->where('decision_id', $decision->id)->where('task_id', $task->id)->count() != 0) {
            $folder = Folder::where('folder_id', null)->where('decision_id', $decision->id)->where('task_id', $task->id)->first();
        } else {
            $folder = Folder::create([
                'task_id' => $task->id,
                'decision_id' => $decision->id,
                'user_id' => auth()->user()->id,
                'folder_id' => null,
                'original_name' => 'user_' . auth()->user()->id,
                'folder_path' => '/storage/' . 'courses/course_' . $course->id . '/task_' . $task->id . '/users/user_' . auth()->user()->id
            ]);
        }

        $path = $folder->folder_path . '/' . $credentials['title'];

        if (Storage::disk('public')->exists(mb_substr($path, 9))) {
            $i = 1;

            do {
                $title = $credentials['title'] . '_' . $i;
                $i++;
            } while (Folder::where('task_id', $task->id)->where('decision_id', $decision->id)->where('folder_id', $folder->id)->where('original_name', $title)->count() != 0);

            $path = $folder->folder_path . '/' . $title;
            $credentials['title'] = $title;
        }


        Storage::disk('public')->makeDirectory(mb_substr($path, 9));

        $fold = Folder::create([
            'task_id' => $task->id,
            'decision_id' => $decision->id,
            'user_id' => auth()->user()->id,
            'folder_id' => $folder->id,
            'original_name' => $credentials['title'],
            'folder_path' => $path,
        ]);

        return new FolderResource($fold->loadMissing([
            'decision'
        ]));
    }

    public function decisionShow(Course $course, Task $task, Decision $decision, Folder $folder)
    {
        $folder->loadMissing([
            'folders',
            'folder',
            'files'
        ]);

        return new FolderResource($folder);
    }

    public function taskShow(Course $course, Task $task, Folder $folder)
    {
        $folder->loadMissing([
            'folders',
            'folder',
            'files'
        ]);


        return new FolderResource($folder);
    }

    public function taskMainShow(Course $course, Task $task)
    {
        $folder = Folder::where('task_id', $task->id)->where('folder_id', null)->where('user_id', $course->leader_id)->with([
            'folders',
            'folder',
            'files'
        ])->first();

        if ($folder == null) {
            return response([]);
        }

        return new FolderResource($folder);
    }

    public function update(Request $request, Folder $decisionFolder)
    {
        //
    }

    public function TaskFolderDestroy(Course $course, Task $task, Folder $folder)
    {
        try {
            $path = substr($folder->folder_path, 9);
            if (Storage::disk('public')->exists($path) && $folder->user_id == auth()->user()->id) {
                Storage::disk('public')->deleteDirectory($path);
                $folder->delete();
                return response(['success_message' => 'Файл успешно удален']);
            }
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку']);
        }
    }

    public function taskFolderDownload(Course $course, Task $task, Folder $folder)
    {
        $zip = new ZipArchive;

        $fileName = `$folder->original_name.zip`;

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
            $files = File::files(mb_substr(public_path($folder->folder_path), 9));

            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }

            $zip->close();
        }

        return response()->download(public_path($fileName));
    }

    public function DecisionFolderDestroy(Course $course, Task $task, Decision $decision, Folder $folder)
    {
        try {
            $path = substr($folder->folder_path, 9);
            if (Storage::disk('public')->exists($path) && $folder->user_id == auth()->user()->id) {
                Storage::disk('public')->deleteDirectory($path);
                $folder->delete();
                return response(['success_message' => 'Файл успешно удален']);
            }
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку']);
        }
    }
}
