<?php

declare(strict_types=1);

namespace App\Filament\Resources\Properties\Pages;

use App\Enums\Locale;
use App\Filament\Resources\Properties\PropertyResource;
use App\Models\Property;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewProperty extends ViewRecord
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewOnWebsite')
                ->label(__('View on website'))
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(fn (): string => route('properties.show', [
                    'locale' => Locale::default()->value,
                    'slug' => (string) $this->getRecord()->id,
                ]))
                ->openUrlInNewTab(),
        ];
    }

    public function getRecord(): Property
    {
        /** @var Property $record */
        $record = parent::getRecord();

        return $record;
    }
}
