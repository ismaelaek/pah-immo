<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties;

use App\Enums\NavigationGroup;
use App\Filament\Resources\Properties\Pages\ListProperties;
use App\Filament\Resources\Properties\Pages\ViewProperty;
use App\Filament\Resources\Properties\RelationManagers\AreasRelationManager;
use App\Filament\Resources\Properties\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\Properties\RelationManagers\MediasRelationManager;
use App\Filament\Resources\Properties\RelationManagers\RegulationsRelationManager;
use App\Filament\Resources\Properties\Schemas\PropertyInfolist;
use App\Filament\Resources\Properties\Tables\PropertiesTable;
use App\Models\Property;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * Read-only view of the properties imported from Apimo: Apimo is the source of truth, so they are edited there.
 */
class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::RealEstate;

    public static function getNavigationLabel(): string
    {
        return __('Properties');
    }

    public static function getModelLabel(): string
    {
        return __('property');
    }

    public static function getPluralModelLabel(): string
    {
        return __('properties');
    }

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        if (! $record instanceof Property) {
            return null;
        }

        return $record->reference ?? '#'.$record->apimo_id;
    }

    /**
     * @return list<string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['reference', 'city_name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return static::getRecordTitle($record) ?? '';
    }

    public static function infolist(Schema $schema): Schema
    {
        return PropertyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AreasRelationManager::class,
            RegulationsRelationManager::class,
            DocumentsRelationManager::class,
            MediasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProperties::route('/'),
            'view' => ViewProperty::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['comments', 'pictures.media']);
    }
}
