<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use App\Models\Post;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columnSpan(2)
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('Title')),
                        TextEntry::make('description')
                            ->label(__('Description')),
                        TextEntry::make('body')
                            ->label(__('Content'))
                            ->markdown(),
                    ]),
                Section::make()
                    ->columnSpan(1)
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('image')
                            ->label(__('Image'))
                            ->collection(MediaCollection::Images->value)
                            ->conversion(MediaConversion::Medium->value),
                        TextEntry::make('slug')
                            ->label(__('Slug')),
                        TextEntry::make('user.name')
                            ->label(__('Author')),
                        IconEntry::make('enabled')
                            ->label(__('Published'))
                            ->boolean(),
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
                            ->visible(fn (Post $record): bool => $record->trashed()),
                    ]),
            ]);
    }
}
