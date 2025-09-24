<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Plan Details')
                    ->schema([
                        Forms\Components\Select::make('plan_type')
                            ->options([
                                'prepaid' => 'Prepaid',
                                'postpaid' => 'Postpaid',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->required(),

                        Forms\Components\TextInput::make('sub_title'),

                        Forms\Components\TextInput::make('tag'),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Duration')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('currency')
                            ->default('USD')
                            ->required(),

                        Forms\Components\Select::make('duration_type')
                            ->options([
                                'day' => 'Day',
                                'week' => 'Week',
                                'month' => 'Month',
                                'year' => 'Year',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('duration_value')
                            ->numeric()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Features')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->label('Plan Features')
                            ->schema([
                                Forms\Components\FileUpload::make('icon_url')
                                    ->label('Feature Icon')
                                    ->disk('public')             // ✅ ensure saved in public
                                    ->directory('features')      // ✅ goes to storage/app/public/features
                                    ->image()
                                    ->visibility('public')       // ✅ URL accessible via /storage/features/...
                                    ->maxSize(2048)              // 2MB
                                    ->required(),

                                Forms\Components\TextInput::make('text')
                                    ->label('Feature Text')
                                    ->required(),
                            ])
                            ->collapsible()
                            ->columns(2),
                    ]),

                Forms\Components\Section::make('Status & Order')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'deleted' => 'Deleted',
                            ])
                            ->default('active'),

                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('plan_type')->badge(),

                Tables\Columns\TextColumn::make('price')
                    ->money(fn ($record) => $record->currency),

                Tables\Columns\TextColumn::make('duration_value')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->duration_type),

                Tables\Columns\TextColumn::make('status')->badge(),

                Tables\Columns\ViewColumn::make('features')
                    ->label('Features')
                    ->view('filament.tables.columns.plan-features'),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
