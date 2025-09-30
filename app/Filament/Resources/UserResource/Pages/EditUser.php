<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
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

    /**
     * Mutate data before saving
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Roles are handled separately, so remove from $data
        if (isset($data['roles']) && is_array($data['roles'])) {
            $data['roles'] = $data['roles'][0]; // keep first if multiple
        }

        unset($data['roles']);

        return $data;
    }

    /**
     * Handle roles after save
     */
    protected function afterSave(): void
    {
        $roles = $this->form->getState()['roles'] ?? null;

        if ($roles === null) {
            // If no change, keep current roles
            $currentRoles = $this->record->roles->pluck('name')->toArray();
            $this->record->syncRoles($currentRoles);
            return;
        }

        $roleId = is_array($roles) ? $roles[0] : $roles;
        $role = Role::find($roleId);

        if ($role) {
            $this->record->syncRoles([$role->name]);
        }
    }
}
