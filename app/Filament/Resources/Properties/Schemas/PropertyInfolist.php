<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\Schemas;

use App\Enums\MediaConversion;
use App\Models\Property;
use App\Models\PropertyPicture;
use App\Models\PropertyService;
use App\Services\Apimo\CatalogLabels;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PropertyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        $catalogLabels = app(CatalogLabels::class);

        return $schema
            ->columns(12)
            ->components([
                Group::make()
                    ->columnSpan(['default' => 12, 'lg' => 8])
                    ->schema([
                        Section::make(__('Descriptions'))
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                RepeatableEntry::make('comments')
                                    ->hiddenLabel()
                                    ->placeholder('-')
                                    ->schema([
                                        TextEntry::make('language')
                                            ->label(__('Language'))
                                            ->badge(),
                                        TextEntry::make('title')
                                            ->label(__('Title'))
                                            ->placeholder('-'),
                                        TextEntry::make('hook')
                                            ->label(__('Catchphrase'))
                                            ->placeholder('-'),
                                        TextEntry::make('comment')
                                            ->label(__('Description'))
                                            ->extraAttributes(['class' => 'whitespace-pre-line'])
                                            ->placeholder('-'),
                                    ]),
                            ]),
                        Section::make(__('Location'))
                            ->icon(Heroicon::OutlinedMapPin)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('city_name')
                                    ->label(__('City'))
                                    ->placeholder('-'),
                                TextEntry::make('city_zipcode')
                                    ->label(__('Zip code'))
                                    ->placeholder('-'),
                                TextEntry::make('district_name')
                                    ->label(__('Neighborhood'))
                                    ->placeholder('-'),
                                TextEntry::make('address')
                                    ->label(__('Address'))
                                    ->placeholder('-'),
                                TextEntry::make('latitude')
                                    ->label(__('Latitude')),
                                TextEntry::make('longitude')
                                    ->label(__('Longitude')),
                            ]),
                        Section::make(__('Media'))
                            ->icon(Heroicon::OutlinedPhoto)
                            ->schema([
                                ImageEntry::make('picture_urls')
                                    ->hiddenLabel()
                                    ->state(fn (Property $record): array => $record->pictures
                                        ->map(fn (PropertyPicture $picture): string => $picture->imageUrl(MediaConversion::Medium))
                                        ->all())
                                    ->placeholder('-'),
                            ]),
                        Section::make(__('Equipment'))
                            ->icon(Heroicon::OutlinedSparkles)
                            ->schema([
                                TextEntry::make('service_labels')
                                    ->hiddenLabel()
                                    ->state(fn (Property $record): array => $record->services
                                        ->map(fn (PropertyService $service): ?string => $catalogLabels->label('property_service', $service->type))
                                        ->filter()
                                        ->values()
                                        ->all())
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
                                    ->copyable()
                                    ->placeholder('-'),
                                TextEntry::make('apimo_id')
                                    ->label(__('Apimo ID'))
                                    ->copyable(),
                                TextEntry::make('agency_name')
                                    ->label(__('Agency'))
                                    ->state(fn (Property $record): string => $record->owningAgency->name ?? (string) $record->agency),
                            ]),
                        Section::make(__('Characteristics'))
                            ->icon(Heroicon::OutlinedHomeModern)
                            ->schema([
                                TextEntry::make('category')
                                    ->label(__('Offer type'))
                                    ->badge()
                                    ->formatStateUsing(fn (int $state): string => $catalogLabels->label('property_category', $state) ?? (string) $state),
                                TextEntry::make('type')
                                    ->label(__('Property type'))
                                    ->formatStateUsing(fn (int $state): string => $catalogLabels->label('property_type', $state) ?? (string) $state),
                                TextEntry::make('price_value')
                                    ->label(__('Price'))
                                    ->money(fn (Property $record): string => $record->price_currency, decimalPlaces: 0)
                                    ->placeholder('-'),
                                IconEntry::make('price_hide')
                                    ->label(__('Hidden on the website'))
                                    ->boolean(),
                                TextEntry::make('area_value')
                                    ->label(__('Area'))
                                    ->numeric(decimalPlaces: 0)
                                    ->suffix(' m²')
                                    ->placeholder('-'),
                                TextEntry::make('rooms')
                                    ->label(__('Rooms')),
                                TextEntry::make('bedrooms')
                                    ->label(__('Bedrooms')),
                                TextEntry::make('bathrooms')
                                    ->label(__('Bathrooms'))
                                    ->state(fn (Property $record): int => $record->bathroomCount()),
                            ]),
                        Section::make(__('History'))
                            ->icon(Heroicon::OutlinedClock)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label(__('Created at'))
                                    ->dateTime(config('app.datetime_format')),
                                TextEntry::make('updated_at')
                                    ->label(__('Updated at'))
                                    ->dateTime(config('app.datetime_format')),
                            ]),
                    ]),
            ]);
    }
}
