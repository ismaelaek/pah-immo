<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaConversion;
use App\Http\Resources\BlogPostResource;
use App\Models\Post;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class BlogController extends Controller
{
    public function index(): InertiaResponse
    {
        $posts = Post::query()
            ->enabled()
            ->with(['user.media', 'media'])
            ->latest()
            ->orderByDesc('id')
            ->get()
            ->map(fn (Post $post): array => (new BlogPostResource($post))->resolve())
            ->all();

        return Inertia::render('Blog', [
            'posts' => $posts,
        ]);
    }

    public function show(string $locale, string $slug): InertiaResponse
    {
        $post = Post::query()
            ->enabled()
            ->with(['user.media', 'media'])
            ->where('slug', $slug)
            ->first();

        return Inertia::render('BlogShow', [
            'post' => $post === null
                ? null
                : (new BlogPostResource($post, MediaConversion::Large))->resolve(),
        ]);
    }

    public function recordView(string $locale, string $slug): Response
    {
        Post::withoutTimestamps(function () use ($slug): void {
            Post::query()
                ->enabled()
                ->where('slug', $slug)
                ->increment('views');
        });

        return response()->noContent();
    }
}
