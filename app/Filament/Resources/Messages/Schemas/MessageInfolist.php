<?php

declare(strict_types=1);

namespace App\Filament\Resources\Messages\Schemas;

use App\Models\Message;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make()
                    ->columnSpan(2)
                    ->schema([
                        TextEntry::make('full_name')
                            ->label(__('Name')),
                        TextEntry::make('email')
                            ->label(__('Email'))
                            ->copyable()
                            ->url(fn (Message $record): string => 'mailto:'.$record->email),
                        TextEntry::make('phone')
                            ->label(__('Phone'))
                            ->copyable()
                            ->url(fn (Message $record): string => 'tel:'.$record->phone),
                        TextEntry::make('message')
                            ->label(__('Message'))
                            ->columnSpanFull(),
                    ]),
                Section::make()
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('locale')
                            ->label(__('Language'))
                            ->badge(),
                        TextEntry::make('created_at')
                            ->label(__('Received at'))
                            ->dateTime(config('app.datetime_format'))
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
