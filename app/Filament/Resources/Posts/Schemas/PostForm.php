<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Models\User;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->maxLength(156)
                            ->helperText(__('A short description of the post (will be used in meta tags).')),
                        MarkdownEditor::make('body')
                            ->label(__('Content'))
                            ->required(),
                    ]),
                Section::make()
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->maxLength(255)
                            ->nullable()
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? $state : null)
                            ->helperText(__('Will be used in the URL of the post. Leave empty to generate slug automatically from title.')),
                        Select::make('user_id')
                            ->label(__('Author'))
                            ->required()
                            ->options(fn (): array => User::query()->pluck('name', 'id')->all())
                            ->searchable()
                            ->default(fn (): ?int => auth()->id()),
                        SpatieMediaLibraryFileUpload::make('image')
                            ->label(__('Image'))
                            ->disk('public')
                            ->collection(MediaCollection::Images->value)
                            ->conversion(MediaConversion::Small->value)
                            ->image()
                            ->maxSize(1024 * 20)
                            ->imageEditor()
                            ->downloadable()
                            ->openable(),
                        Toggle::make('enabled')
                            ->label(__('Published'))
                            ->default(true),
                    ]),
            ]);
    }
}
