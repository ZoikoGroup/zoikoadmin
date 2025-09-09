<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Spatie\Permission\Models\Role;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        $statusOptions = [
            0 => 'In-active',
            1 => 'Active',
        ];

        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->email(),
                Select::make('roles')
                    ->label('User Role')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false),
                Select::make('status')
                    ->label('Status')
                    ->options($statusOptions)
                    ->required()
                    ->native(false),
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['roles']); // Roles handled separately
        return $data;
    }

    protected function afterSave(): void
    {
        $roleId = $this->form->getState()['roles'];
        $roleName = \Spatie\Permission\Models\Role::find($roleId)?->name;

        if ($roleName) {
            $this->record->syncRoles($roleName);
        }
    }

}
