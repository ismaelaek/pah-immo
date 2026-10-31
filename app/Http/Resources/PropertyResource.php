<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\MediaConversion;
use App\Models\Property;
use App\Models\PropertyPicture;
use App\Models\PropertyService;
use App\Services\Apimo\CatalogLabels;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * Presents an Apimo property to the public pages.
 *
 * Needs the `comments`, `services`, `areas` and `pictures.media` relations loaded.
 *
 * @mixin Property
 */
class PropertyResource extends JsonResource
{
    public const array RELATIONS = ['comments', 'services', 'areas', 'pictures.media'];

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
     *     price: float|null,
     *     pricePeriod: string|null,
     *     area: float|null,
     *     bedrooms: int|null,
     *     bathrooms: int|null,
     *     neighborhood: string|null,
     *     city: string,
     *     latitude: float,
     *     longitude: float,
     *     equipment: array<int, array{value: string, label: string}>,
     *     images: array<int, array{small: string, medium: string, large: string}>,
     *     image: string|null,
     *     imageAlt: string
     * }
     */
    public function toArray(Request $request): array
    {
        $catalogs = app(CatalogLabels::class);
        $comment = $this->commentIn(app()->getLocale());
        $typeLabel = $catalogs->label('property_type', $this->type) ?? '';
        $title = $comment->title ?? trim("{$typeLabel} {$this->city_name}");
        $offerType = $this->offerType();
        $bathrooms = $this->bathroomCount();
        $images = $this->pictures
            ->filter(fn (PropertyPicture $picture): bool => $picture->internet === '1')
            ->map(fn (PropertyPicture $picture): array => [
                'small' => $picture->imageUrl(MediaConversion::Small),
                'medium' => $picture->imageUrl(MediaConversion::Medium),
                'large' => $picture->imageUrl(MediaConversion::Large),
            ])
            ->values();

        return [
            'slug' => $this->routeSlug($title),
            'reference' => $this->reference ?? (string) $this->apimo_id,
            'title' => $title,
            'description' => $comment->hook ?? Str::limit((string) $comment?->comment, 160),
            'body' => $request->routeIs('properties.show') ? $this->paragraphs($comment->comment_full ?? $comment?->comment) : null,
            'type' => [
                'value' => (string) $this->type,
                'label' => $typeLabel,
            ],
            'offerType' => [
                'value' => $offerType->value,
                'label' => $this->text($offerType->getLabel()),
            ],
            'price' => $this->price_hide ? null : $this->price_value,
            'pricePeriod' => $catalogs->label('property_period', $this->price_period),
            'area' => $this->area_value,
            'bedrooms' => $this->bedrooms > 0 ? $this->bedrooms : null,
            'bathrooms' => $bathrooms > 0 ? $bathrooms : null,
            'neighborhood' => $this->district_name,
            'city' => $this->city_name ?? '',
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'equipment' => $this->services
                ->map(fn (PropertyService $service): array => [
                    'value' => (string) $service->type,
                    'label' => (string) $catalogs->label('property_service', $service->type),
                ])
                ->filter(fn (array $equipment): bool => $equipment['label'] !== '')
                ->values()
                ->all(),
            'images' => $images->all(),
            'image' => $images->first()[$this->imageConversion->value] ?? null,
            'imageAlt' => $title,
        ];
    }

    private function text(string|Htmlable|null $label): string
    {
        return $label instanceof Htmlable ? $label->toHtml() : (string) $label;
    }

    /**
     * Apimo descriptions are plain text: escape them and keep their paragraphs and line breaks.
     */
    private function paragraphs(?string $text): ?string
    {
        if ($text === null || trim($text) === '') {
            return null;
        }

        return collect(preg_split('/\R{2,}/', trim($text)) ?: [])
            ->map(fn (string $paragraph): string => '<p>'.nl2br(e(trim($paragraph)), false).'</p>')
            ->implode("\n");
    }
}
