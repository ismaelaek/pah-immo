<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\Schemas;

use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Models\Property;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PropertyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Group::make()
                    ->columnSpan(['default' => 12, 'lg' => 8])
                    ->schema([
                        Section::make(__('General'))
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                TextEntry::make('title')
                                    ->label(__('Title')),
                                TextEntry::make('description')
                                    ->label(__('Description')),
                                TextEntry::make('body')
                                    ->label(__('Content'))
                                    ->markdown(),
                            ]),
                        Section::make(__('Media'))
                            ->icon(Heroicon::OutlinedPhoto)
                            ->schema([
                                SpatieMediaLibraryImageEntry::make('images')
                                    ->hiddenLabel()
                                    ->collection(MediaCollection::Images->value)
                                    ->conversion(MediaConversion::Medium->value)
                                    ->placeholder('-'),
                            ]),
                        Section::make(__('Equipment'))
                            ->icon(Heroicon::OutlinedSparkles)
                            ->schema([
                                TextEntry::make('equipment')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->placeholder('-'),
                            ]),
                    ]),
                Group::make()
                    ->columnSpan(['default' => 12, 'lg' => 4])
                    ->schema([
                        Section::make(__('Identification'))
                            ->icon(Heroicon::OutlinedIdentification)
                            ->schema([
                                TextEntry::make('reference')
                                    ->label(__('Reference'))
                                    ->copyable(),
                                TextEntry::make('slug')
                                    ->label(__('Slug')),
                            ]),
                        Section::make(__('Characteristics'))
                            ->icon(Heroicon::OutlinedHomeModern)
                            ->schema([
                                TextEntry::make('offer_type')
                                    ->label(__('Offer type'))
                                    ->badge(),
                                TextEntry::make('type')
                                    ->label(__('Property type')),
                                TextEntry::make('price')
                                    ->label(__('Price'))
                                    ->money('EUR', decimalPlaces: 0),
                                TextEntry::make('area')
                                    ->label(__('Area'))
                                    ->suffix(' m²'),
                                TextEntry::make('bedrooms')
                                    ->label(__('Bedrooms'))
                                    ->placeholder('-'),
                                TextEntry::make('bathrooms')
                                    ->label(__('Bathrooms'))
                                    ->placeholder('-'),
                            ]),
                        Section::make(__('Location'))
                            ->icon(Heroicon::OutlinedMapPin)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('city')
                                    ->label(__('City')),
                                TextEntry::make('neighborhood')
                                    ->label(__('Neighborhood'))
                                    ->placeholder('-'),
                                TextEntry::make('latitude')
                                    ->label(__('Latitude'))
                                    ->placeholder('-'),
                                TextEntry::make('longitude')
                                    ->label(__('Longitude'))
                                    ->placeholder('-'),
                            ]),
                        Section::make(__('History'))
                            ->icon(Heroicon::OutlinedClock)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label(__('Created at'))
                                    ->dateTime(config('app.datetime_format'))
                                    ->placeholder('-'),
                                TextEntry::make('updated_at')
                                    ->label(__('Updated at'))
                                    ->dateTime(config('app.datetime_format'))
                                    ->placeholder('-'),
                                TextEntry::make('deleted_at')
                                    ->label(__('Deleted at'))
                                    ->dateTime(config('app.datetime_format'))
                                    ->visible(fn (Property $record): bool => $record->trashed()),
                            ]),
                    ]),
            ]);
    }
}
