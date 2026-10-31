<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\NavigationGroup;
use App\Filament\Pages\Schemas\AgencyInfolist;
use App\Models\Agency;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmptyState;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Read-only details of the agency connected to the Apimo account. They are edited in Apimo and refreshed by the sync.
 */
class AgencyInformation extends Page
{
    protected string $view = 'filament-panels::pages.page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::Settings;

    protected static ?string $slug = 'agency';

    public static function getNavigationLabel(): string
    {
        return __('Agency information');
    }

    public function getTitle(): string
    {
        return __('Agency information');
    }

    public function content(Schema $schema): Schema
    {
        $agency = $this->agency();

        if ($agency === null) {
            return $schema->components([
                EmptyState::make(__('No agency yet'))
                    ->description(__('The agency information appears here after the first Apimo sync.'))
                    ->icon(Heroicon::OutlinedBuildingOffice2),
            ]);
        }

        return AgencyInfolist::configure($schema->record($agency));
    }

    /**
     * The Apimo account is connected to a single agency: the first active one.
     */
    private function agency(): ?Agency
    {
        return Agency::query()->where('active', true)->orderBy('id')->first();
    }
}
