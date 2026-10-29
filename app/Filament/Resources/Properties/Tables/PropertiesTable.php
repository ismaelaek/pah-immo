<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\Tables;

use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Enums\OfferType;
use App\Enums\PropertyType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PropertiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->label(__('Image'))
                    ->collection(MediaCollection::Images->value)
                    ->conversion(MediaConversion::Small->value)
                    ->limit(1)
                    ->square(),
                TextColumn::make('reference')
                    ->label(__('Reference'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('offer_type')
                    ->label(__('Offer type'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('Property type'))
                    ->sortable(),
                TextColumn::make('city')
                    ->label(__('City'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('EUR', decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('area')
                    ->label(__('Area'))
                    ->suffix(' m²')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime(config('app.datetime_format'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([12, 24, 48, 96])
            ->defaultPaginationPageOption(12)
            ->filters([
                SelectFilter::make('offer_type')
                    ->label(__('Offer type'))
                    ->options(OfferType::class),
                SelectFilter::make('type')
                    ->label(__('Property type'))
                    ->options(PropertyType::class)
                    ->multiple(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
