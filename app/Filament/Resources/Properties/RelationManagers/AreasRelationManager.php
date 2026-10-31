<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\RelationManagers;

use App\Services\Apimo\CatalogLabels;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AreasRelationManager extends RelationManager
{
    protected static string $relationship = 'areas';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Rooms');
    }

    public function table(Table $table): Table
    {
        $catalogLabels = app(CatalogLabels::class);

        return $table
            ->columns([
                TextColumn::make('type')
                    ->label(__('Room'))
                    ->formatStateUsing(fn (int $state): string => $catalogLabels->label('property_areas', $state) ?? (string) $state),
                TextColumn::make('number')
                    ->label(__('Number')),
                TextColumn::make('area')
                    ->label(__('Area'))
                    ->numeric(decimalPlaces: 1)
                    ->suffix(' m²')
                    ->placeholder('-'),
                TextColumn::make('flooring')
                    ->label(__('Flooring'))
                    ->formatStateUsing(fn (int $state): string => $catalogLabels->label('property_flooring', $state) ?? (string) $state)
                    ->placeholder('-'),
                TextColumn::make('floor_value')
                    ->label(__('Floor'))
                    ->placeholder('-'),
            ])
            ->paginated(false);
    }
}
