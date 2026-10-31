<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\MediaCollection;
use App\Models\PropertyPicture;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

/**
 * Downloads an Apimo picture into the picture's `images` media collection, replacing any previous file.
 * Picture URLs are files, not API routes, so this does not spend Apimo request quota.
 */
class DownloadPropertyPicture implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * @var list<int>
     */
    public array $backoff = [60, 600];

    public int $timeout = 120;

    public bool $deleteWhenMissingModels = true;

    public function __construct(public PropertyPicture $picture) {}

    public function uniqueId(): string
    {
        return (string) $this->picture->id;
    }

    public function handle(): void
    {
        $url = $this->picture->url;

        if ($this->picture->hasImageFrom($url)) {
            return;
        }

        $image = Http::connectTimeout(10)->timeout(90)->get($url)->throw()->body();

        $this->picture
            ->addMediaFromString($image)
            ->usingFileName(basename((string) parse_url($url, PHP_URL_PATH)) ?: "picture-{$this->picture->apimo_id}")
            ->withCustomProperties(['source_url' => $url])
            ->toMediaCollection(MediaCollection::Images->value);
    }
}
