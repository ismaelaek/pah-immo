<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaConversion;
use App\Http\Resources\BlogPostResource;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
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

    public function show(string $slug): Response
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
}
