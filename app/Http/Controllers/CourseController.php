<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');
            return response()->json(['path' => '/storage/' . $path]);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function store(Request $request)
{ // Debugging line to check the request data
    $request->validate([
        'title' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $course = Course::create([
        'title' => $request->title,
        'category' => $request->category,
        'description' => $request->description,
    ]);

    if ($request->has('modules')) {
        foreach ($request->modules as $moduleData) {
            $module = $course->modules()->create([
                'title' => $moduleData['title'] ?? 'Untitled Module',
            ]);

            if (!empty($moduleData['contents'])) {
                foreach ($moduleData['contents'] as $contentData) {
                    $module->contents()->create([
                        'type' => $contentData['type'] ?? null,
                        'title' => $contentData['title'] ?? '',
                        'description' => $contentData['description'] ?? '',
                        'video_url' => $contentData['video_url'] ?? '',
                        'external_link' => $contentData['external_link'] ?? '',
                        'image' => $contentData['image'] ?? '',
                    ]);
                }
            }
        }
    }

    return response()->json(['message' => 'Course created successfully.']);
}


}
