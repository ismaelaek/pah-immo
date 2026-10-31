<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\Tables;

use App\Enums\MediaConversion;
use App\Models\Property;
use App\Services\Apimo\CatalogLabels;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PropertiesTable
{
    public static function configure(Table $table): Table
    {
        $catalogLabels = app(CatalogLabels::class);

        return $table
            ->columns([
                ImageColumn::make('cover')
                    ->label(__('Image'))
                    ->state(fn (Property $record): ?string => $record->pictures->first()?->imageUrl(MediaConversion::Small))
                    ->square(),
                TextColumn::make('reference')
                    ->label(__('Reference'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->state(fn (Property $record): ?string => $record->commentIn(app()->getLocale())?->title)
                    ->limit(50)
                    ->placeholder('-'),
                TextColumn::make('category')
                    ->label(__('Offer type'))
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => $catalogLabels->label('property_category', $state) ?? (string) $state)
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('Property type'))
                    ->formatStateUsing(fn (int $state): string => $catalogLabels->label('property_type', $state) ?? (string) $state)
                    ->sortable(),
                TextColumn::make('city_name')
                    ->label(__('City'))
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('price_value')
                    ->label(__('Price'))
                    ->money(fn (Property $record): string => $record->price_currency, decimalPlaces: 0)
                    ->sortable()
                    ->placeholder('-'),
                IconColumn::make('price_hide')
                    ->label(__('Hidden on the website'))
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('area_value')
                    ->label(__('Area'))
                    ->numeric(decimalPlaces: 0)
                    ->suffix(' m²')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime(config('app.datetime_format'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->paginated([12, 24, 48, 96])
            ->defaultPaginationPageOption(12)
            ->filters([
                SelectFilter::make('category')
                    ->label(__('Offer type'))
                    ->options(fn (): array => collect($catalogLabels->options('property_category'))->pluck('label', 'value')->all()),
                SelectFilter::make('type')
                    ->label(__('Property type'))
                    ->options(fn (): array => collect($catalogLabels->options('property_type'))->pluck('label', 'value')->all())
                    ->multiple(),
                SelectFilter::make('city_name')
                    ->label(__('City'))
                    ->options(fn (): array => collect(Property::cities())->mapWithKeys(fn (string $city): array => [$city => $city])->all())
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
