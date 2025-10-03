<?php

namespace App\Filament\Resources\PlanTypeResource\Pages;

use App\Filament\Resources\PlanTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanTypes extends ListRecords
{
    protected static string $resource = PlanTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
