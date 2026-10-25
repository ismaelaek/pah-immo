<?php

declare(strict_types=1);

namespace App\Filament\Resources\Messages\Tables;

use App\Enums\Locale;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->label(__('Read'))
                    ->boolean()
                    ->true(Heroicon::OutlinedEnvelopeOpen, 'gray')
                    ->false(Heroicon::Envelope, 'primary')
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label(__('Name'))
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['last_name', 'first_name']),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('locale')
                    ->label(__('Language'))
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('Received at'))
                    ->dateTime(config('app.datetime_format'))
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_read')
                    ->label(__('Read'))
                    ->trueLabel(__('Read'))
                    ->falseLabel(__('Unread')),
                SelectFilter::make('locale')
                    ->label(__('Language'))
                    ->options(Locale::class),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
