<?php

declare(strict_types=1);

namespace App\Filament\Resources\Messages\Pages;

use App\Filament\Resources\Messages\MessageResource;
use App\Models\Message;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Livewire\Component;

class ViewMessage extends ViewRecord
{
    protected static string $resource = MessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('markAsRead')
                ->label(__('Mark as read'))
                ->icon(Heroicon::OutlinedEnvelopeOpen)
                ->visible(fn (): bool => ! $this->getRecord()->is_read)
                ->action(function (Component $livewire): void {
                    $this->getRecord()->update(['is_read' => true]);
                    $livewire->dispatch('refresh-sidebar');
                })
                ->color('success'),
            Action::make('markAsUnread')
                ->label(__('Mark as unread'))
                ->icon(Heroicon::Envelope)
                ->visible(fn (): bool => $this->getRecord()->is_read)
                ->action(function (Component $livewire): void {
                    $this->getRecord()->update(['is_read' => false]);
                    $livewire->dispatch('refresh-sidebar');
                })
                ->color('warning'),
            DeleteAction::make(),
        ];
    }

    public function getRecord(): Message
    {
        /** @var Message $record */
        $record = parent::getRecord();

        return $record;
    }
}
