<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanTypeResource\Pages;
use App\Models\PlanType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class PlanTypeResource extends Resource
{
    protected static ?string $model = PlanType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Products';
    protected static ?string $navigationLabel = 'Plan Types';
    protected static ?string $pluralModelLabel = 'Plan Types';
    protected static ?string $modelLabel = 'Plan Type';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('parent_id')
                    ->label('Parent Plan Type')
                    ->options(PlanType::all()->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),

                TextInput::make('name')
                    ->label('Plan Type Name')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $currentSlug = $get('slug');
                        $generatedSlug = Str::slug($state);

                        // Only auto-update slug if empty or equals previous generated slug
                        if (empty($currentSlug) || $currentSlug === Str::slug($currentSlug)) {
                            $set('slug', $generatedSlug);
                        }
                    }),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->hint('Auto-generated from name but editable')
                    ->unique(PlanType::class, 'slug', ignoreRecord: true), // ✅ unique validation
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('parent.name')->label('Parent')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('slug')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Created')->dateTime(),
                TextColumn::make('updated_at')->label('Updated')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanTypes::route('/'),
            'create' => Pages\CreatePlanType::route('/create'),
            'edit' => Pages\EditPlanType::route('/{record}/edit'),
        ];
    }
}
