<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\RelationManagers;

use App\Models\PropertyMedia;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MediasRelationManager extends RelationManager
{
    protected static string $relationship = 'medias';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Videos and virtual tours');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                TextColumn::make('value')
                    ->label(__('Link'))
                    ->limit(60)
                    ->url(fn (PropertyMedia $record): ?string => filter_var($record->value, FILTER_VALIDATE_URL) !== false ? $record->value : null, shouldOpenInNewTab: true),
                IconColumn::make('public')
                    ->label(__('Public'))
                    ->state(fn (PropertyMedia $record): bool => $record->internet === '1')
                    ->boolean(),
            ])
            ->paginated(false);
    }
}
