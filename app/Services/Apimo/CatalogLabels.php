<?php

declare(strict_types=1);

namespace App\Services\Apimo;

use App\Enums\Locale;
use App\Models\CatalogEntry;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;

/**
 * Translates Apimo catalog values (property types, services...) into labels in the visitor's language.
 *
 * Catalog cultures are full codes (`fr_FR`, `en_GB`); a locale matches the cultures starting with it and
 * falls back to the default locale. Each catalog is loaded once per request.
 */
#[Scoped]
class CatalogLabels
{
    /**
     * @var array<string, Collection<int, string>>
     */
    private array $loaded = [];

    public function label(string $catalog, int|string|null $value, ?string $locale = null): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $this->labels($catalog, $locale)->get((int) $value);
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public function options(string $catalog, ?string $locale = null): array
    {
        return $this->labels($catalog, $locale)
            ->map(fn (string $label, int $value): array => ['value' => (string) $value, 'label' => $label])
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, string>
     */
    private function labels(string $catalog, ?string $locale): Collection
    {
        $locale ??= app()->getLocale();

        return $this->loaded["{$catalog}:{$locale}"] ??= $this->load($catalog, $locale);
    }

    /**
     * @return Collection<int, string>
     */
    private function load(string $catalog, string $locale): Collection
    {
        $entries = CatalogEntry::query()
            ->where('catalog', $catalog)
            ->orderBy('apimo_id')
            ->get(['apimo_id', 'culture', 'name']);

        $inLocale = fn (string $code): Collection => $entries
            ->filter(fn (CatalogEntry $entry): bool => str_starts_with($entry->culture, $code))
            ->mapWithKeys(fn (CatalogEntry $entry): array => [$entry->apimo_id => $entry->name]);

        return $inLocale(Locale::default()->value)->replace($inLocale($locale));
    }
}
