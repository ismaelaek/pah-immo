<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\MediaConversion;
use App\Enums\PriceRange;
use App\Enums\RoomFilter;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Services\Apimo\CatalogLabels;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PropertyController extends Controller
{
    private const int MAP_RESULTS_PER_PAGE = 20;

    private const string FILTERS_SESSION_KEY = 'property_filters';

    private const array FILTER_KEYS = ['type', 'price', 'city', 'rooms'];

    public function __construct(private readonly CatalogLabels $catalogLabels) {}

    public function index(Request $request): Response
    {
        $filters = $this->searchFilters($request);

        $properties = Property::query()
            ->with(PropertyResource::RELATIONS)
            ->matchingSearch(...$filters)
            ->latest()
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Property $property): array => (new PropertyResource($property))->resolve());

        return Inertia::render('Properties', [
            'properties' => $properties,
            'filters' => $this->filterValues($filters),
            'propertyTypes' => $this->catalogLabels->options('property_type'),
            'cities' => Property::cities(),
        ]);
    }

    public function map(Request $request): Response
    {
        $filters = $this->searchFilters($request);
        $bounds = $this->mapBounds($request->string('bounds')->toString());

        $query = Property::query()
            ->matchingSearch(...$filters)
            ->when($bounds, fn (Builder $query, array $bounds): Builder => $query->withinBounds(...$bounds));

        $properties = $query
            ->with(PropertyResource::RELATIONS)
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
            'propertyTypes' => fn (): array => $this->catalogLabels->options('property_type'),
            'cities' => fn (): array => Property::cities(),
        ]);
    }

    /**
     * The slug starts with the local id; any other slug for the same property redirects to the current one.
     */
    public function show(Request $request, string $locale, string $slug): Response|RedirectResponse
    {
        $id = Property::idFromRouteSlug($slug);

        $property = $id === null
            ? null
            : Property::query()->with(PropertyResource::RELATIONS)->find($id);

        if ($property === null) {
            return Inertia::render('PropertyShow', [
                'property' => null,
                'similarProperties' => [],
            ]);
        }

        $resource = (new PropertyResource($property, MediaConversion::Large))->resolve($request);

        if ($resource['slug'] !== $slug) {
            return redirect()->route('properties.show', ['locale' => $locale, 'slug' => $resource['slug']], 301);
        }

        return Inertia::render('PropertyShow', [
            'property' => $resource,
            'similarProperties' => Property::query()
                ->with(PropertyResource::RELATIONS)
                ->whereKeyNot($property->id)
                ->where(fn (Builder $query): Builder => $query
                    ->where('city_id', $property->city_id)
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
     * Read the search filters from the request, or restore the ones saved in
     * the session when the request carries none. `reset` clears them.
     *
     * @return array{type: int|null, priceRange: PriceRange|null, rooms: RoomFilter|null, city: string}
     */
    private function searchFilters(Request $request): array
    {
        if ($request->boolean('reset')) {
            $request->session()->forget(self::FILTERS_SESSION_KEY);
        } elseif ($request->hasAny(self::FILTER_KEYS)) {
            $request->session()->put(self::FILTERS_SESSION_KEY, $request->only(self::FILTER_KEYS));
        }

        /** @var array<string, mixed> $values */
        $values = $request->hasAny(self::FILTER_KEYS)
            ? $request->only(self::FILTER_KEYS)
            : $request->session()->get(self::FILTERS_SESSION_KEY, []);

        $value = fn (string $key): string => is_string($values[$key] ?? null) ? trim($values[$key]) : '';

        return [
            'type' => ctype_digit($value('type')) ? (int) $value('type') : null,
            'priceRange' => PriceRange::tryFrom($value('price')),
            'rooms' => RoomFilter::tryFrom($value('rooms')),
            'city' => $value('city'),
        ];
    }

    /**
     * @param  array{type: int|null, priceRange: PriceRange|null, rooms: RoomFilter|null, city: string}  $filters
     * @return array{type: string, price: string, city: string, rooms: string}
     */
    private function filterValues(array $filters): array
    {
        return [
            'type' => $filters['type'] === null ? '' : (string) $filters['type'],
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
