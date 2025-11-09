<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->baseNewsQuery($request)
            ->latest()
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role_id, [1, 3])) {
            abort(403, 'You are not allowed to create news.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1024'],
            'content' => ['required', 'string'],
        ]);

        $news = new News();
        $news->title = $validated['title'];
        $news->description = $validated['description'];
        $news->content = $validated['content'];
        $news->user_id = $user->id;
        $news->save();

        $resource = $this->baseNewsQuery($request)->findOrFail($news->id);

        return response()->json($resource, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        return $this->baseNewsQuery($request)
            ->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Prepare the base query for fetching news with like metadata.
     */
    private function baseNewsQuery(Request $request)
    {
        $user = $request->user();

        return News::query()
            ->with('user')
            ->withCount('likes')
            ->withExists([
                'likes as is_liked' => function ($query) use ($user) {
                    $query->where('user_id', $user?->id);
                },
            ]);
    }
}
