<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreRequest;
use App\Http\Resources\Review\ReviewResource;
use App\Models\Course;
use App\Models\Decision;
use App\Models\File;
use App\Models\Review;
use App\Models\Task;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Course $course, Task $task, Decision $decision, File $file)
    {
        if (($decision->completed_id != 2 && $decision->completed_id != 1) || $course->leader_id == auth()->user()->id) {
            $reviews = $file->reviews;
            return ReviewResource::collection($reviews);
        }

        return response(['data' => []]);
    }

    public function store(StoreRequest $request, Course $course, Task $task, Decision $decision, File $file)
    {
        $credentials = $request->validated();

        Review::create([
            'file_id' => $file->id,
            'creator_id' => auth()->user()->id,
            'start' => $credentials['start'],
            'end' => $credentials['end'],
            'color' => $credentials['color'],
            'title' => $credentials['title'],
            'description' => isset($credentials['description']) ? $credentials['description'] : null,
        ]);

        return response([]);
    }
}
