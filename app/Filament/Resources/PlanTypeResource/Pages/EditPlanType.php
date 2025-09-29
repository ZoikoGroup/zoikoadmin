<?php

namespace App\Filament\Resources\PlanTypeResource\Pages;

use App\Filament\Resources\PlanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanType extends EditRecord
{
    protected static string $resource = PlanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
