<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Post
 */
class BlogPostResource extends JsonResource
{
    public function __construct(
        $resource,
        private MediaConversion $imageConversion = MediaConversion::Medium,
    ) {
        parent::__construct($resource);
    }

    /**
     * @return array{
     *     slug: string,
     *     title: string,
     *     description: string,
     *     body: string|null,
     *     author: string,
     *     authorAvatar: string|null,
     *     publishedAt: string|null,
     *     publishedAtFormatted: string|null,
     *     image: string|null,
     *     imageAlt: string,
     *     views: int
     * }
     */
    public function toArray(Request $request): array
    {
        $image = $this->getFirstMedia(MediaCollection::Images->value);

        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'body' => $request->routeIs('blog.show') ? Str::markdown($this->body) : null,
            'author' => $this->user->name,
            'authorAvatar' => $this->user->getFilamentAvatarUrl(),
            'publishedAt' => $this->created_at?->toIso8601String(),
            'publishedAtFormatted' => $this->created_at?->format(config('app.datetime_format')),
            'image' => $this->mediaUrl($image, $this->imageConversion),
            'imageAlt' => $this->title,
            'views' => $this->views,
        ];
    }

    private function mediaUrl(?Media $media, MediaConversion $conversion): ?string
    {
        if ($media === null) {
            return null;
        }

        $name = $conversion->value;

        $url = $media->hasGeneratedConversion($name)
            ? $media->getUrl($name)
            : $media->getUrl();

        return $url.'?v='.$media->updated_at?->timestamp;
    }
}
