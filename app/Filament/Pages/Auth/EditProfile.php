<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use App\Enums\MediaCollection;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use SensitiveParameter;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getAvatarFormComponent(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }

    protected function getRedirectUrl(): ?string
    {
        return static::getUrl();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, #[SensitiveParameter] array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);

        $this->form->model($record)->saveRelationships();

        return $record;
    }

    protected function getAvatarFormComponent(): Component
    {
        return SpatieMediaLibraryFileUpload::make('avatar')
            ->label('Avatar')
            ->collection(MediaCollection::Avatar->value)
            ->disk('public')
            ->visibility('public')
            ->image()
            ->imageEditor()
            ->maxSize(5120);
    }
}
