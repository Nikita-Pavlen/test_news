<?php

namespace App\Http\Controllers;

use App\Events\AuthorNotification;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, News $news): JsonResponse
    {
        $user = $request->user();

        $like = $news->likes()->firstOrCreate(
            ['user_id' => $user->id],
            ['created_at' => now()]
        );

        if ($like->wasRecentlyCreated) {
            $news->loadMissing('user');

            $author = $news->user;

            if ($author && $author->isNot($user)) {
                event(new AuthorNotification(
                    authorId: $author->id,
                    message: "{$user->name} liked \"{$news->title}\"",
                    meta: [
                        'news_id' => $news->id,
                        'liker_id' => $user->id,
                    ],
                ));
            }
        }

        return $this->buildResponse($news, true);
    }

    public function destroy(Request $request, News $news): JsonResponse
    {
        $user = $request->user();

        $news->likes()
            ->where('user_id', $user->id)
            ->delete();

        return $this->buildResponse($news, false);
    }

    private function buildResponse(News $news, bool $isLiked): JsonResponse
    {
        $likesCount = $news->likes()->count();

        return response()->json([
            'likes_count' => $likesCount,
            'is_liked' => $isLiked,
        ]);
    }
}

