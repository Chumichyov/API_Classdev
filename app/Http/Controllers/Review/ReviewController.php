<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreRequest;
use App\Http\Requests\Review\UpdateRequest;
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
        $reviews = Review::orderBy('start', 'asc')->where('file_id', $file->id)->get();

        return ReviewResource::collection($reviews);
    }

    public function store(StoreRequest $request, Course $course, Task $task, Decision $decision, File $file)
    {
        $credentials = $request->validated();

        Review::create([
            'file_id' => $file->id,
            'folder_id' => $file->folder->id,
            'creator_id' => auth()->user()->id,
            'start' => $credentials['start'],
            'end' => $credentials['end'],
            'color' => $credentials['color'],
            'title' => $credentials['title'],
            'description' => isset($credentials['description']) ? $credentials['description'] : null,
        ]);

        return response([]);
    }

    public function update(UpdateRequest $request, Course $course, Task $task, Decision $decision, File $file, Review $review)
    {
        $credentials = $request->validated();

        $review->update($credentials);

        return new ReviewResource($review);
    }

    public function destroy(Course $course, Task $task, Decision $decision, File $file, Review $review)
    {
        $review->delete();

        return response([]);
    }
}
