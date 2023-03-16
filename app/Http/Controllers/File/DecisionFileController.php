<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileRequest;
use App\Http\Resources\File\FileResource;
use App\Models\Course;
use App\Models\Decision;
use App\Models\File;
use App\Models\Folder;
use App\Models\FileExtension;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psy\CodeCleaner\EmptyArrayDimFetchPass;
use ZipArchive;

class DecisionFileController extends Controller
{
    public function index(Course $course, Task $task, Decision $decision)
    {
        return FileResource::collection($decision->files);
    }

    public function store(FileRequest $request, Course $course, Task $task, Decision $decision)
    {
        try {
            $credentials = $request->validated();
            foreach ($credentials['files'] as $file) {
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                if ($extension == 'zip') {
                    $path = 'courses/course_' . $course->id . '/task_' . $task->id . '/user_' . auth()->user()->id . '/' . $originalName;
                    Folder::create([
                        'decision_id' => $decision->id,
                        'folder_id' => null,
                        'folder_path' => '/storage/' . 'public/' . $path
                    ]);

                    $zip = new ZipArchive();
                    $status = $zip->open($file->getRealPath());

                    if ($status !== true) {
                        throw new \Exception($status);
                    }

                    $zip->extractTo(Storage::path('public/' . $path));

                    foreach (Storage::allDirectories('public/' . $path) as $zipFolder) {
                        Folder::create([
                            'decision_id' => $decision->id,
                            'folder_id' => Folder::where('folder_path', '/storage/' . pathinfo($zipFolder, PATHINFO_DIRNAME))->first()->id,
                            'folder_path' => '/storage/' . $zipFolder
                        ]);
                    }

                    foreach (Storage::allFiles('public/' . $path) as $zipFile) {
                        $fileName = md5(microtime()) . '.' . pathinfo($zipFile, PATHINFO_EXTENSION);
                        Storage::move($zipFile, pathinfo($zipFile, PATHINFO_DIRNAME) . '/' . $fileName);

                        File::create([
                            'decision_id' => $decision->id,
                            'user_id' => auth()->user()->id,
                            'folder_id' => Folder::where('folder_path', '/storage/' . pathinfo($zipFile, PATHINFO_DIRNAME))->first()->id,
                            'file_extension_id' => !is_null(FileExtension::where('extension', pathinfo($zipFile, PATHINFO_EXTENSION))->first()) ? FileExtension::where('extension', pathinfo($zipFile, PATHINFO_EXTENSION))->first()->id : null,
                            'original_name' => pathinfo($zipFile, PATHINFO_FILENAME),
                            'file_name' => $fileName,
                            'file_path' => '/storage/' . pathinfo($zipFile, PATHINFO_DIRNAME) . '/' . $fileName,
                        ]);
                    }

                    $zip->close();
                } else {
                    $path = 'courses/course_' . $course->id . '/task_' . $task->id . '/user_' . auth()->user()->id;

                    $fileName = md5(microtime()) . '.' . $extension;
                    $sendedFile = $file->storeAs('public/' . $path, $fileName);

                    File::create([
                        'decision_id' => $decision->id,
                        'user_id' => auth()->user()->id,
                        'file_extension_id' => !is_null(FileExtension::where('extension', $extension)->first()) ? FileExtension::where('extension', $extension)->first()->id : null,
                        'original_name' => $file->getClientOriginalName(),
                        'file_name' => $fileName,
                        'file_path' => '/storage/' . pathinfo($sendedFile, PATHINFO_DIRNAME) . '/' . $fileName,
                    ]);
                }
            }

            return response(['success_message' => 'Файлы успешно загружены.']);
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку.']);
        }
    }

    public function show(File $File)
    {
        //
    }

    public function update(Request $request, File $File)
    {
        //
    }

    public function destroy(Course $course, Task $task, Decision $decision, File $file)
    {
        try {
            $path = substr($file->file_path, 16);

            if (Storage::disk('public')->exists($path) && $file->user_id == auth()->user()->id) {

                Storage::disk('public')->delete($path);
                $file->delete();
                return response(['success_message' => 'Файл успешно удален.']);
            }
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку.']);
        }
    }
}
