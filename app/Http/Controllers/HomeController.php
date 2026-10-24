<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PropertyType;
use App\Http\Resources\BlogPostResource;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $latestPosts = Post::query()
            ->enabled()
            ->with(['user.media', 'media'])
            ->latest()
            ->orderByDesc('id')
            ->limit(3)
            ->get()
            ->map(fn (Post $post): array => (new BlogPostResource($post))->resolve())
            ->all();

        return Inertia::render('Home', [
            'propertyTypes' => PropertyType::options(),
            'latestPosts' => $latestPosts,
        ]);
    }
}
