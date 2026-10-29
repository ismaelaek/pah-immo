<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PropertyType;
use App\Http\Resources\BlogPostResource;
use App\Http\Resources\PropertyResource;
use App\Models\Post;
use App\Models\Property;
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

        $featuredProperties = Property::query()
            ->with('media')
            ->latest()
            ->orderByDesc('id')
            ->limit(6)
            ->get()
            ->map(fn (Property $property): array => (new PropertyResource($property))->resolve())
            ->all();

        return Inertia::render('Home', [
            'propertyTypes' => PropertyType::options(),
            'cities' => Property::cities(),
            'featuredProperties' => $featuredProperties,
            'latestPosts' => $latestPosts,
        ]);
    }
}
