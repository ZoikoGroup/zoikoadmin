<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Filters\SelectFilter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Products';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'This product name already exists.',
                ]),

            Select::make('product_type')
                ->options([
                    'digital' => 'Digital',
                    'physical' => 'Physical',
                ])
                ->required(),

            FileUpload::make('image_url')
                ->disk('public')
                ->directory('products')
                ->image()
                ->maxSize(2048)
                ->label('Product Image'),

            Forms\Components\RichEditor::make('description')
                ->label('Description')
                ->columnSpanFull(),

            Forms\Components\RichEditor::make('short_description')
                ->label('Short Description')
                ->columnSpanFull(),

            TextInput::make('price_uk')->numeric()->required(),
            TextInput::make('price_usa')->numeric()->required(),
            TextInput::make('discount')->numeric()->nullable(),

            Toggle::make('featured')->label('Featured'),

            Select::make('product_category_id')
                ->relationship('productCategory', 'name')
                ->label('Category')
                ->searchable()
                ->required(),

            Select::make('product_discount_type_id')
                ->relationship('discountType', 'name')
                ->label('Discount Type')
                ->searchable(),

            // ✅ Plan Dropdown
            Select::make('plan_id')
                ->relationship('plan', 'title')
                ->label('Plan')
                ->searchable()
                ->preload()
                ->required(), // remove if plan is optional

            // ✅ Attributes Repeater
            Repeater::make('productAttributes')
                ->label('Product Attributes')
                ->relationship('productAttributes')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        TextInput::make('name')->label('Attribute Name')->required(),
                        TextInput::make('value')->label('Value')->required(),
                        TextInput::make('unit')->label('Unit'),
                    ]),
                ])
                ->columns(1)
                ->defaultItems(1)
                ->addActionLabel('Add Attribute')
                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                ->cloneable()
                ->reorderable()
                ->disableLabel()
                ->columnSpanFull()
                ->hidden(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->disk('public')
                    ->square()
                    ->label('Image'),

                TextColumn::make('name')->searchable()->sortable(),

                TextColumn::make('price_uk')->label('UK Price')->sortable(),
                TextColumn::make('price_usa')->label('USA Price')->sortable(),
                TextColumn::make('discount')->label('Discount')->sortable(),

                TextColumn::make('productCategory.name')->label('Category')->sortable()->toggleable(),
                TextColumn::make('discountType.name')->label('Discount Type')->sortable()->toggleable(),
                TextColumn::make('plan.title')->label('Plan')->sortable()->toggleable(),

                BooleanColumn::make('featured')->label('Featured')->sortable(),
            ])
            ->filters([
                SelectFilter::make('product_category_id')
                    ->label('Category')
                    ->relationship('productCategory', 'name'),

                SelectFilter::make('product_discount_type_id')
                    ->label('Discount Type')
                    ->relationship('discountType', 'name'),

                SelectFilter::make('plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'title'),

                SelectFilter::make('featured')
                    ->label('Featured')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
