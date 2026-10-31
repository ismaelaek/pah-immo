<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\RelationManagers;

use App\Services\Apimo\CatalogLabels;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RegulationsRelationManager extends RelationManager
{
    protected static string $relationship = 'regulations';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Diagnostics');
    }

    public function table(Table $table): Table
    {
        $catalogLabels = app(CatalogLabels::class);

        return $table
            ->columns([
                TextColumn::make('type')
                    ->label(__('Diagnostic'))
                    ->formatStateUsing(fn (int $state): string => $catalogLabels->label('property_regulation', $state) ?? (string) $state),
                TextColumn::make('value')
                    ->label(__('Value')),
                TextColumn::make('label')
                    ->label(__('Rating'))
                    ->badge()
                    ->placeholder('-'),
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date(config('app.date_format'))
                    ->placeholder('-'),
            ])
            ->paginated(false);
    }
}
