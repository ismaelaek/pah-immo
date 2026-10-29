<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaConversion;
use App\Enums\PriceRange;
use App\Enums\PropertyType;
use App\Enums\RoomFilter;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PropertyController extends Controller
{
    private const int MAP_RESULTS_PER_PAGE = 20;

    public function index(Request $request): Response
    {
        $filters = $this->searchFilters($request);

        $properties = Property::query()
            ->with('media')
            ->matchingSearch(...$filters)
            ->latest()
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Property $property): array => (new PropertyResource($property))->resolve());

        return Inertia::render('Properties', [
            'properties' => $properties,
            'filters' => $this->filterValues($filters),
            'propertyTypes' => PropertyType::options(),
            'cities' => Property::cities(),
        ]);
    }

    public function map(Request $request): Response
    {
        $filters = $this->searchFilters($request);
        $bounds = $this->mapBounds($request->string('bounds')->toString());

        $query = Property::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->matchingSearch(...$filters)
            ->when($bounds, fn (Builder $query, array $bounds): Builder => $query->withinBounds(...$bounds));

        $properties = $query
            ->with('media')
            ->latest()
            ->orderByDesc('id')
            ->paginate(self::MAP_RESULTS_PER_PAGE)
            ->withQueryString()
            ->through(fn (Property $property): array => (new PropertyResource($property, MediaConversion::Small))->resolve());

        return Inertia::render('PropertiesMap', [
            'properties' => Inertia::scroll($properties),
            'total' => $properties->total(),
            'bounds' => $bounds,
            'filters' => $this->filterValues($filters),
            'propertyTypes' => fn (): array => PropertyType::options(),
            'cities' => fn (): array => Property::cities(),
        ]);
    }

    public function show(string $locale, string $slug): Response
    {
        $property = Property::query()
            ->with('media')
            ->where('slug', $slug)
            ->first();

        return Inertia::render('PropertyShow', [
            'property' => $property === null
                ? null
                : (new PropertyResource($property, MediaConversion::Large))->resolve(),
            'similarProperties' => $property === null
                ? []
                : Property::query()
                    ->with('media')
                    ->whereKeyNot($property->id)
                    ->where(fn (Builder $query): Builder => $query
                        ->where('city', $property->city)
                        ->orWhere('type', $property->type))
                    ->latest()
                    ->orderByDesc('id')
                    ->limit(3)
                    ->get()
                    ->map(fn (Property $similar): array => (new PropertyResource($similar))->resolve())
                    ->all(),
        ]);
    }

    /**
     * @return array{type: PropertyType|null, priceRange: PriceRange|null, rooms: RoomFilter|null, city: string}
     */
    private function searchFilters(Request $request): array
    {
        return [
            'type' => PropertyType::tryFrom($request->string('type')->toString()),
            'priceRange' => PriceRange::tryFrom($request->string('price')->toString()),
            'rooms' => RoomFilter::tryFrom($request->string('rooms')->toString()),
            'city' => $request->string('city')->trim()->toString(),
        ];
    }

    /**
     * @param  array{type: PropertyType|null, priceRange: PriceRange|null, rooms: RoomFilter|null, city: string}  $filters
     * @return array{type: string, price: string, city: string, rooms: string}
     */
    private function filterValues(array $filters): array
    {
        return [
            'type' => $filters['type']->value ?? '',
            'price' => $filters['priceRange']->value ?? '',
            'city' => $filters['city'],
            'rooms' => $filters['rooms']->value ?? '',
        ];
    }

    /**
     * Parse a "south,west,north,east" bounding box sent by the map.
     *
     * @return array{south: float, west: float, north: float, east: float}|null
     */
    private function mapBounds(string $bounds): ?array
    {
        $coordinates = explode(',', $bounds);

        if (count($coordinates) !== 4 || array_filter($coordinates, fn (string $coordinate): bool => ! is_numeric($coordinate)) !== []) {
            return null;
        }

        [$south, $west, $north, $east] = array_map(floatval(...), $coordinates);

        $south = max($south, -90.0);
        $north = min($north, 90.0);
        $west = max($west, -180.0);
        $east = min($east, 180.0);

        if ($south >= $north || $west >= $east) {
            return null;
        }

        return [
            'south' => $south,
            'west' => $west,
            'north' => $north,
            'east' => $east,
        ];
    }
}
