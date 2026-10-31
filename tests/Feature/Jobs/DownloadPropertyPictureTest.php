<?php

declare(strict_types=1);

use App\Enums\MediaCollection;
use App\Jobs\DownloadPropertyPicture;
use App\Models\PropertyPicture;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Http::preventStrayRequests();
});

test('it stores the apimo picture as the picture image and remembers its source url', function () {
    $picture = PropertyPicture::factory()->create(['url' => 'https://d1.apimo.pro/pictures/66321497.jpg']);
    Http::fake(['d1.apimo.pro/*' => Http::response(UploadedFile::fake()->image('photo.jpg', 1920, 1440)->getContent())]);

    DownloadPropertyPicture::dispatchSync($picture);

    $media = $picture->refresh()->getFirstMedia(MediaCollection::Images->value);

    expect($media->file_name)->toBe('66321497.jpg')
        ->and($media->getCustomProperty('source_url'))->toBe($picture->url)
        ->and($media->getGeneratedConversions()->filter()->keys()->all())->toEqualCanonicalizing(['small', 'medium', 'large']);
});

test('it does not download an image that is already stored from the same url', function () {
    $picture = PropertyPicture::factory()->create(['url' => 'https://d1.apimo.pro/pictures/66321497.jpg']);
    $picture->addMedia(UploadedFile::fake()->image('photo.jpg'))
        ->withCustomProperties(['source_url' => $picture->url])
        ->toMediaCollection(MediaCollection::Images->value);
    Http::fake();

    DownloadPropertyPicture::dispatchSync($picture);

    Http::assertNothingSent();
});
