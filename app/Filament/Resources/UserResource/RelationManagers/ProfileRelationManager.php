<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProfileRelationManager extends RelationManager
{
    protected static string $relationship = 'profile';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')->required(),
            Forms\Components\TextInput::make('last_name')->required(),
            Forms\Components\TextInput::make('nickname'),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Select::make('locale')
                ->options([
                    'en' => 'English',
                    'fr' => 'French',
                    'es' => 'Spanish',
                ])
                ->default('en'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('nickname'),
                Tables\Columns\TextColumn::make('locale'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
