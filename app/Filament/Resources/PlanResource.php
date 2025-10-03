<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Models\Plan;
use App\Models\PlanType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Plan Details')
                    ->schema([
                        TextInput::make('bq_id')
                            ->label('Bequick ID')
                            ->maxLength(50)
                            ->nullable(),

                        Select::make('plan_type_id')
                            ->label('Plan Type')
                            ->options(PlanType::all()->pluck('name', 'id'))
                            ->required(),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! empty($state)) {
                                    $slug = Str::slug($state);
                                    $set('slug', $slug);
                                    $set('meta_slug', $slug); // auto-copy to meta_slug
                                    $set('meta_title', $state); // auto-copy to meta_title
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(150),

                        TextInput::make('sub_title')
                            ->maxLength(255),

                        TextInput::make('tag')
                            ->maxLength(50),

                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255),

                        TextInput::make('meta_slug')
                            ->label('Meta Slug')
                            ->maxLength(255),

                        TextInput::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(255),

                        FileUpload::make('image_url')
                            ->label('Plan Image')
                            ->disk('public')
                            ->directory('plans')
                            ->image()
                            ->visibility('public')
                            ->maxSize(2048),
                    ])
                    ->columns(2),

                Section::make('Pricing & Duration')
                    ->schema([
                        TextInput::make('price')
                            ->numeric()
                            ->required(),

                        TextInput::make('currency')
                            ->default('USD')
                            ->required()
                            ->maxLength(10),

                        Select::make('duration_type')
                            ->options([
                                'day' => 'Day',
                                'week' => 'Week',
                                'month' => 'Month',
                                'year' => 'Year',
                            ])
                            ->default('month')
                            ->required(),

                        TextInput::make('duration_value')
                            ->numeric()
                            ->label('Duration Value'),
                    ])
                    ->columns(2),

                Section::make('Features')
                    ->schema([
                        Repeater::make('features')
                            ->label('Plan Features')
                            ->schema([
                                FileUpload::make('icon_url')
                                    ->label('Feature Icon')
                                    ->disk('public')
                                    ->directory('features')
                                    ->image()
                                    ->visibility('public')
                                    ->maxSize(2048),

                                TextInput::make('text')
                                    ->label('Feature Text'),
                            ])
                            ->collapsible()
                            ->columns(2)
                            ->nullable(),
                    ]),

                Section::make('Status & Order')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                                'deleted' => 'Deleted',
                            ])
                            ->default('active'),

                        TextInput::make('order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('slug')->label('Slug')->sortable()->searchable(),
                TextColumn::make('planType.name')->label('Plan Type')->sortable()->searchable(),
                TextColumn::make('price')->money(fn ($record) => $record->currency),
                TextColumn::make('duration_value')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . $record->duration_type),
                TextColumn::make('meta_title')->label('Meta Title')->sortable()->searchable(),
                TextColumn::make('meta_slug')->label('Meta Slug')->sortable()->searchable(),
                TextColumn::make('status')->badge(),
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
