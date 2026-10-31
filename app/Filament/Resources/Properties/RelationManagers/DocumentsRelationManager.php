<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\RelationManagers;

use App\Models\PropertyDocument;
use App\Services\Apimo\CatalogLabels;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Documents');
    }

    public function table(Table $table): Table
    {
        $catalogLabels = app(CatalogLabels::class);

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->icon(Heroicon::OutlinedDocument)
                    ->url(fn (PropertyDocument $record): string => $record->download_url, shouldOpenInNewTab: true),
                TextColumn::make('type_id')
                    ->label(__('Type'))
                    ->formatStateUsing(fn (string $state): string => $catalogLabels->label('document_type', $state) ?? $state)
                    ->placeholder('-'),
                TextColumn::make('filesize')
                    ->label(__('Size'))
                    ->formatStateUsing(fn (string $state): string => number_format((int) $state / 1024, 0, ',', ' ').' Ko'),
                IconColumn::make('public')
                    ->label(__('Public'))
                    ->state(fn (PropertyDocument $record): bool => $record->internet === '1')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime(config('app.datetime_format')),
            ])
            ->paginated(false);
    }
}
