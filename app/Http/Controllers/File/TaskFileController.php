<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Http\Requests\File\FileRequest;
use App\Http\Resources\File\FileResource;
use App\Models\Course;
use App\Models\FileExtension;
use App\Models\Task;
use App\Models\Folder;
use App\Models\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class TaskFileController extends Controller
{
    public function index(Course $course, Task $task)
    {
        return FileResource::collection($task->files);
    }

    public function store(FileRequest $request, Course $course, Task $task)
    {
        try {
            $credentials = $request->validated();
            foreach ($credentials['files'] as $file) {
                $extension = $file->getClientOriginalExtension();
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                if ($extension == 'zip') {
                    $path = 'courses/course_' . $course->id . '/task_' . $task->id . '/files' . '/' . $originalName;

                    if (Folder::where('task_id', $task->id)->where('folder_id', null)->count() != 0) {
                        $main = Folder::where('task_id', $task->id)->where('folder_id', null)->first();
                    } else {
                        $main = Folder::create([
                            'task_id' => $task->id,
                            'folder_id' => null,
                            'original_name' => 'files',
                            'folder_path' => '/storage/' . 'courses/course_' . $course->id . '/task_' . $task->id . '/files'
                        ]);
                    }

                    Folder::create([
                        'task_id' => $task->id,
                        'folder_id' => $main->id,
                        'original_name' => $originalName,
                        'folder_path' => '/storage/' . $path
                    ]);

                    $zip = new ZipArchive();
                    $status = $zip->open($file->getRealPath());

                    if ($status !== true) {
                        throw new \Exception($status);
                    }

                    $zip->extractTo(Storage::path('public/' . $path));

                    mb_internal_encoding("UTF-8");

                    foreach (Storage::allDirectories('public/' . $path) as $zipFolder) {
                        $fold = Folder::create([
                            'task_id' => $task->id,
                            'folder_id' => Folder::where('folder_path', '/storage/' . pathinfo(mb_substr($zipFolder, 7), PATHINFO_DIRNAME))->first()->id,
                            'original_name' => pathinfo($zipFolder, PATHINFO_BASENAME),
                            'folder_path' => '/storage/' . mb_substr($zipFolder, 7)
                        ]);
                    }


                    foreach (Storage::allFiles('public/' . $path) as $zipFile) {
                        $fileName = md5(microtime()) . '.' . pathinfo($zipFile, PATHINFO_EXTENSION);
                        Storage::move($zipFile, pathinfo($zipFile, PATHINFO_DIRNAME) . '/' . $fileName);

                        File::create([
                            'task_id' => $task->id,
                            'user_id' => auth()->user()->id,
                            'folder_id' => Folder::where('folder_path', '/storage/' . pathinfo(mb_substr($zipFile, 7), PATHINFO_DIRNAME))->first()->id,
                            'file_extension_id' => !is_null(FileExtension::where('extension', pathinfo(mb_substr($zipFile, 7), PATHINFO_EXTENSION))->first()) ? FileExtension::where('extension', pathinfo($zipFile, PATHINFO_EXTENSION))->first()->id : null,
                            'original_name' => pathinfo(mb_substr($zipFile, 7), PATHINFO_BASENAME),
                            'file_name' => $fileName,
                            'file_path' => '/storage/' . pathinfo(mb_substr($zipFile, 7), PATHINFO_DIRNAME) . '/' . $fileName,
                        ]);
                    }

                    $zip->close();
                } else {
                    $path = 'courses/course_' . $course->id . '/task_' . $task->id . '/files';

                    if (Folder::where('task_id', $task->id)->where('folder_id', null)->count() != 0) {
                        $main = Folder::where('task_id', $task->id)->where('folder_id', null)->first();
                    } else {
                        $main = Folder::create([
                            'task_id' => $task->id,
                            'folder_id' => null,
                            'original_name' => 'files',
                            'folder_path' => '/storage/' . 'courses/course_' . $course->id . '/task_' . $task->id . '/files'
                        ]);
                    }

                    $fileName = md5(microtime()) . '.' . $extension;
                    $sendedFile = $file->storeAs('public/' . $path, $fileName);

                    File::create([
                        'task_id' => $task->id,
                        'folder_id' => $main->id,
                        'user_id' => auth()->user()->id,
                        'file_extension_id' => !is_null(FileExtension::where('extension', $extension)->first()) ? FileExtension::where('extension', $extension)->first()->id : null,
                        'original_name' => $file->getClientOriginalName(),
                        'file_name' => $fileName,
                        'file_path' => '/storage/' . pathinfo(mb_substr($sendedFile, 7), PATHINFO_DIRNAME) . '/' . $fileName,
                    ]);
                }
            }

            return response(['success_message' => 'Файлы успешно загружены']);
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку']);
        }
    }

    public function show(Course $course, Task $task, File $file)
    {
        $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief', 'jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

        $extension = pathinfo($file->original_name, PATHINFO_EXTENSION);

        if (in_array($extension, $imageExtensions)) {
            //Image
            return response()->file(public_path($file->file_path));
        }

        // File with code
        $content = fopen(public_path($file->file_path), 'r');

        while (!feof($content)) {
            $lines[] = str_replace(PHP_EOL, '', fgets($content));
        }

        fclose($content);

        return response([
            "data" => [
                'file' => new FileResource($file),
                'lines' => $lines
            ]
        ]);
    }

    public function update(Request $request, File $file)
    {
        //
    }

    public function destroy(Course $course, Task $task, File $file)
    {
        try {
            $path = substr($file->file_path, 16);
            if (Storage::disk('public')->exists($path) && $file->user_id == auth()->user()->id) {

                Storage::disk('public')->delete($path);
                $file->delete();
                return response(['success_message' => 'Файл успешно удален']);
            }
        } catch (Exception $e) {
            return response(['error_message' => 'Непредвиденная ошибка. Пожалуйста, повторите попытку']);
        }
    }
}
