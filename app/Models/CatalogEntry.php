<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CatalogEntryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A value of an Apimo API v3 catalog (GET /catalogs/{catalog_id}). `catalog` is the catalog name, `apimo_id` the value's `id`.
 *
 * @property int $id
 * @property string $catalog
 * @property int $apimo_id
 * @property string $culture
 * @property string $name
 * @property string|null $name_plurial
 * @property string|null $value
 */
class CatalogEntry extends Model
{
    /** @use HasFactory<CatalogEntryFactory> */
    use HasFactory;

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'apimo_id' => 'integer',
        ];
    }
}
