<?php

declare(strict_types=1);

namespace App\Filament\Pages\Schemas;

use App\Models\Agency;
use App\Services\Apimo\CatalogLabels;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

class AgencyInfolist
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
                        Section::make(__('Presentation'))
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                RepeatableEntry::make('stories')
                                    ->hiddenLabel()
                                    ->placeholder('-')
                                    ->schema([
                                        TextEntry::make('language')
                                            ->label(__('Language'))
                                            ->badge(),
                                        TextEntry::make('title')
                                            ->label(__('Title'))
                                            ->placeholder('-'),
                                        TextEntry::make('content')
                                            ->label(__('Content'))
                                            ->extraAttributes(['class' => 'whitespace-pre-line'])
                                            ->placeholder('-'),
                                    ]),
                                TextEntry::make('timetable')
                                    ->label(__('Opening hours'))
                                    ->extraAttributes(['class' => 'whitespace-pre-line'])
                                    ->placeholder('-'),
                            ]),
                        Section::make(__('Fee schedule'))
                            ->icon(Heroicon::OutlinedReceiptPercent)
                            ->schema([
                                RepeatableEntry::make('rates')
                                    ->hiddenLabel()
                                    ->placeholder('-')
                                    ->columns(5)
                                    ->schema([
                                        TextEntry::make('category')
                                            ->label(__('Offer type'))
                                            ->formatStateUsing(fn (?string $state): string => $catalogLabels->label('property_category', $state) ?? (string) $state)
                                            ->placeholder('-'),
                                        TextEntry::make('range_min')
                                            ->label(__('From'))
                                            ->placeholder('-'),
                                        TextEntry::make('range_max')
                                            ->label(__('To'))
                                            ->placeholder('-'),
                                        TextEntry::make('commission_price')
                                            ->label(__('Fixed fee'))
                                            ->placeholder('-'),
                                        TextEntry::make('commission_rate')
                                            ->label(__('Fee rate'))
                                            ->suffix(' %')
                                            ->placeholder('-'),
                                    ]),
                            ]),
                        Section::make(__('Media'))
                            ->icon(Heroicon::OutlinedPhoto)
                            ->schema([
                                ImageEntry::make('pictures')
                                    ->hiddenLabel()
                                    ->placeholder('-'),
                            ]),
                    ]),
                Group::make()
                    ->columnSpan(['default' => 12, 'lg' => 4])
                    ->schema([
                        Section::make(__('Identification'))
                            ->icon(Heroicon::OutlinedIdentification)
                            ->schema([
                                ImageEntry::make('logo')
                                    ->hiddenLabel()
                                    ->state(fn (Agency $record): ?string => $record->logo !== '' ? $record->logo : null),
                                TextEntry::make('name')
                                    ->label(__('Name'))
                                    ->weight(FontWeight::SemiBold),
                                TextEntry::make('apimo_id')
                                    ->label(__('Apimo ID'))
                                    ->copyable(),
                                TextEntry::make('reference')
                                    ->label(__('Reference'))
                                    ->placeholder('-'),
                                IconEntry::make('active')
                                    ->label(__('Active'))
                                    ->boolean(),
                                TextEntry::make('company_name')
                                    ->label(__('Company'))
                                    ->placeholder('-'),
                                TextEntry::make('brand_name')
                                    ->label(__('Network'))
                                    ->placeholder('-'),
                            ]),
                        Section::make(__('Contact'))
                            ->icon(Heroicon::OutlinedPhone)
                            ->schema([
                                TextEntry::make('email')
                                    ->label(__('Email'))
                                    ->copyable()
                                    ->placeholder('-'),
                                TextEntry::make('phone')
                                    ->label(__('Phone'))
                                    ->copyable()
                                    ->placeholder('-'),
                                TextEntry::make('url')
                                    ->label(__('Website'))
                                    ->url(fn (Agency $record): ?string => $record->url, shouldOpenInNewTab: true)
                                    ->placeholder('-'),
                            ]),
                        Section::make(__('Location'))
                            ->icon(Heroicon::OutlinedMapPin)
                            ->schema([
                                TextEntry::make('address')
                                    ->label(__('Address'))
                                    ->placeholder('-'),
                                TextEntry::make('city_name')
                                    ->label(__('City'))
                                    ->state(fn (Agency $record): ?string => trim("{$record->city_zipcode} {$record->city_name}") ?: null)
                                    ->placeholder('-'),
                                TextEntry::make('region')
                                    ->label(__('Region'))
                                    ->placeholder('-'),
                                TextEntry::make('country')
                                    ->label(__('Country')),
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
