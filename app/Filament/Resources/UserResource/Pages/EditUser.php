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
                    ->multiple(false)   // Important: single select only
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
        // If roles is array, pick first value (just to be safe)
        if (isset($data['roles']) && is_array($data['roles'])) {
            $data['roles'] = $data['roles'][0];
        }
        unset($data['roles']); // We handle roles separately below
        return $data;
    }

    protected function afterSave(): void
    {
        // Get roles from form state, or null if not present (unchanged)
        $roles = $this->form->getState()['roles'] ?? null;

        if ($roles === null) {
            // Role was not changed - keep current roles synced to avoid errors
            $currentRoles = $this->record->roles->pluck('name')->toArray();
            $this->record->syncRoles($currentRoles);
            return;
        }

        // Normalize $roles to single role id
        $roleId = is_array($roles) ? $roles[0] : $roles;

        $role = Role::find($roleId);

        if ($role) {
            $this->record->syncRoles([$role->name]);
        }
    }
}
