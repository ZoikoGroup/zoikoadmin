<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserProfileResource\Pages;
use App\Models\UserProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class UserProfileResource extends Resource
{
    protected static ?string $model = UserProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Profiles';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->required(),

            Forms\Components\TextInput::make('first_name')->maxLength(100),
            Forms\Components\TextInput::make('last_name')->maxLength(100),
            Forms\Components\TextInput::make('nickname')->maxLength(100),
            Forms\Components\Textarea::make('description'),

            Forms\Components\Select::make('locale')
                ->options([
                    'en' => 'English',
                    'es' => 'Spanish',
                    'fr' => 'French',
                ])
                ->default('en'),

            Forms\Components\TextInput::make('phone')->maxLength(20),
            Forms\Components\TextInput::make('address')->maxLength(255),
            Forms\Components\TextInput::make('city')->maxLength(100),
            Forms\Components\TextInput::make('state')->maxLength(100),
            Forms\Components\TextInput::make('country')->maxLength(100),
            Forms\Components\TextInput::make('postal_code')->maxLength(20),

            Forms\Components\FileUpload::make('avatar')
                ->disk('public')              // ✅ Ensure stored on public disk
                ->directory('avatars')        // ✅ Files in storage/app/public/avatars
                ->image()
                ->maxSize(2048),              // 2MB
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('user.name')->label('User'),
            Tables\Columns\TextColumn::make('first_name'),
            Tables\Columns\TextColumn::make('last_name'),
            Tables\Columns\TextColumn::make('nickname'),
            Tables\Columns\TextColumn::make('phone'),
            Tables\Columns\TextColumn::make('city'),
            Tables\Columns\TextColumn::make('country'),

            Tables\Columns\ImageColumn::make('avatar')
                ->disk('public')     // ✅ Pulls from storage/app/public
                ->circular()
                ->label('Avatar'),

            Tables\Columns\TextColumn::make('locale'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserProfiles::route('/'),
            'create' => Pages\CreateUserProfile::route('/create'),
            'edit' => Pages\EditUserProfile::route('/{record}/edit'),
        ];
    }
}
