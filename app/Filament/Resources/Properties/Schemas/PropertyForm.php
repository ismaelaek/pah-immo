<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\Schemas;

use App\Enums\Equipment;
use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Enums\OfferType;
use App\Enums\PropertyType;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class PropertyForm
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
                                TextInput::make('title')
                                    ->label(__('Title'))
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                                        if ($operation !== 'create') {
                                            return;
                                        }

                                        $set('slug', Str::slug((string) $state));
                                    }),
                                Textarea::make('description')
                                    ->label(__('Description'))
                                    ->required()
                                    ->maxLength(156)
                                    ->helperText(__('A short description of the property (will be used in meta tags).')),
                                MarkdownEditor::make('body')
                                    ->label(__('Content'))
                                    ->required(),
                            ]),
                        Section::make(__('Media'))
                            ->icon(Heroicon::OutlinedPhoto)
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('images')
                                    ->label(__('Images'))
                                    ->disk('public')
                                    ->collection(MediaCollection::Images->value)
                                    ->conversion(MediaConversion::Small->value)
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->panelLayout('grid')
                                    ->maxSize(1024 * 20)
                                    ->imageEditor()
                                    ->downloadable()
                                    ->openable(),
                            ]),
                        Section::make(__('Equipment'))
                            ->icon(Heroicon::OutlinedSparkles)
                            ->schema([
                                CheckboxList::make('equipment')
                                    ->hiddenLabel()
                                    ->options(Equipment::class)
                                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 3])
                                    ->bulkToggleable(),
                            ]),
                    ]),
                Group::make()
                    ->columnSpan(['default' => 12, 'lg' => 4])
                    ->schema([
                        Section::make(__('Identification'))
                            ->icon(Heroicon::OutlinedIdentification)
                            ->schema([
                                TextInput::make('reference')
                                    ->label(__('Reference'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                TextInput::make('slug')
                                    ->label(__('Slug'))
                                    ->maxLength(255)
                                    ->nullable()
                                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? $state : null)
                                    ->helperText(__('Will be used in the URL of the property. Leave empty to generate slug automatically from title.')),
                            ]),
                        Section::make(__('Characteristics'))
                            ->icon(Heroicon::OutlinedHomeModern)
                            ->schema([
                                ToggleButtons::make('offer_type')
                                    ->label(__('Offer type'))
                                    ->options(OfferType::class)
                                    ->default(OfferType::Sale)
                                    ->required()
                                    ->inline(),
                                Select::make('type')
                                    ->label(__('Property type'))
                                    ->options(PropertyType::class)
                                    ->required(),
                                TextInput::make('price')
                                    ->label(__('Price'))
                                    ->required()
                                    ->integer()
                                    ->minValue(0)
                                    ->suffix('€'),
                                TextInput::make('area')
                                    ->label(__('Area'))
                                    ->required()
                                    ->integer()
                                    ->minValue(0)
                                    ->suffix('m²'),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('bedrooms')
                                            ->label(__('Bedrooms'))
                                            ->integer()
                                            ->minValue(0)
                                            ->maxValue(255)
                                            ->nullable(),
                                        TextInput::make('bathrooms')
                                            ->label(__('Bathrooms'))
                                            ->integer()
                                            ->minValue(0)
                                            ->maxValue(255)
                                            ->nullable(),
                                    ]),
                            ]),
                        Section::make(__('Location'))
                            ->icon(Heroicon::OutlinedMapPin)
                            ->schema([
                                TextInput::make('city')
                                    ->label(__('City'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('neighborhood')
                                    ->label(__('Neighborhood'))
                                    ->maxLength(255)
                                    ->nullable(),
                                TextInput::make('latitude')
                                    ->label(__('Latitude'))
                                    ->numeric()
                                    ->minValue(-90)
                                    ->maxValue(90)
                                    ->nullable(),
                                TextInput::make('longitude')
                                    ->label(__('Longitude'))
                                    ->numeric()
                                    ->minValue(-180)
                                    ->maxValue(180)
                                    ->nullable(),
                            ]),
                    ]),
            ]);
    }
}
