<?php

declare(strict_types=1);

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\MediaCollection;
use App\Enums\MediaConversion;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection(MediaCollection::Images->value)
                    ->conversion(MediaConversion::Small->value)
                    ->square(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('user.name')
                    ->label(__('Author'))
                    ->searchable()
                    ->sortable(),
                IconColumn::make('enabled')
                    ->label(__('Published'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('views')
                    ->label(__('Views'))
                    ->sortable()
                    ->numeric(),
                TextColumn::make('created_at')
                    ->dateTime(config('app.datetime_format')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('enabled')
                    ->label(__('Published'))
                    ->trueLabel(__('Published'))
                    ->falseLabel(__('Draft')),
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
