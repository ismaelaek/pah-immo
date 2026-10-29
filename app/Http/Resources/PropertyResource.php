<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\Equipment;
use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @mixin Property
 */
class PropertyResource extends JsonResource
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
     *     reference: string,
     *     title: string,
     *     description: string,
     *     body: string|null,
     *     type: array{value: string, label: string},
     *     offerType: array{value: string, label: string},
     *     price: int,
     *     area: int,
     *     bedrooms: int|null,
     *     bathrooms: int|null,
     *     neighborhood: string|null,
     *     city: string,
     *     latitude: float|null,
     *     longitude: float|null,
     *     equipment: list<array{value: string, label: string}>,
     *     images: list<array{small: string, medium: string, large: string}>,
     *     image: string|null,
     *     imageAlt: string
     * }
     */
    public function toArray(Request $request): array
    {
        $images = $this->getMedia(MediaCollection::Images->value);

        return [
            'slug' => $this->slug,
            'reference' => $this->reference,
            'title' => $this->title,
            'description' => $this->description,
            'body' => $request->routeIs('properties.show') ? Str::markdown($this->body) : null,
            'type' => [
                'value' => $this->type->value,
                'label' => (string) $this->type->getLabel(),
            ],
            'offerType' => [
                'value' => $this->offer_type->value,
                'label' => (string) $this->offer_type->getLabel(),
            ],
            'price' => $this->price,
            'area' => $this->area,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'equipment' => $this->equipment
                ?->map(fn (Equipment $equipment): array => [
                    'value' => $equipment->value,
                    'label' => (string) $equipment->getLabel(),
                ])
                ->values()
                ->all() ?? [],
            'images' => $images
                ->map(fn (Media $media): array => [
                    'small' => $this->mediaUrl($media, MediaConversion::Small),
                    'medium' => $this->mediaUrl($media, MediaConversion::Medium),
                    'large' => $this->mediaUrl($media, MediaConversion::Large),
                ])
                ->values()
                ->all(),
            'image' => $images->isNotEmpty() ? $this->mediaUrl($images->first(), $this->imageConversion) : null,
            'imageAlt' => $this->title,
        ];
    }

    private function mediaUrl(Media $media, MediaConversion $conversion): string
    {
        $name = $conversion->value;

        $url = $media->hasGeneratedConversion($name)
            ? $media->getUrl($name)
            : $media->getUrl();

        return $url.'?v='.$media->updated_at?->timestamp;
    }
}
