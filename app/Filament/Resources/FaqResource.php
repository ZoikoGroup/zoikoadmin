<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Page;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('FAQ Info')
                ->description('Manage the FAQ content')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('question')
                            ->label('Question')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0),
                    ]),

                    RichEditor::make('answer')
                        ->label('Answer')
                        ->required()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'bulletList',
                            'orderedList',
                            'link',
                        ])
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        Select::make('faq_type')
                            ->label('FAQ Type')
                            ->options([
                                'general' => 'General',
                                'product' => 'Product',
                                'billing' => 'Billing',
                                'technical' => 'Technical',
                            ])
                            ->nullable()
                            ->searchable(),

                        // ✅ Multiselect categories
                        Select::make('faq_category')
                            ->label('FAQ Categories')
                            ->multiple()
                            ->options(ProductCategory::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->nullable(),
                    ]),

                    Grid::make(2)->schema([
                        Select::make('product_id')
                            ->label('Related Product')
                            ->options(Product::pluck('name', 'id')->toArray())
                            ->nullable()
                            ->searchable(),

                        Select::make('page_id')
                            ->label('Related Page')
                            ->options(Page::pluck('title', 'id')->toArray())
                            ->nullable()
                            ->searchable(),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('question')
                    ->label('Question')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->question),

                TextColumn::make('answer')
                    ->label('Answer')
                    ->limit(50)
                    ->tooltip(fn ($record) => strip_tags($record->answer)),

                TextColumn::make('faq_type')
                    ->label('Type')
                    ->sortable()
                    ->toggleable(),

                // ✅ Show categories as tags
                TagsColumn::make('faq_category')
                    ->label('Categories')
                    ->getStateUsing(function ($record) {
                        if (empty($record->faq_category) || !is_array($record->faq_category)) {
                            return [];
                        }
                        return ProductCategory::whereIn('id', $record->faq_category)
                            ->pluck('name')
                            ->toArray();
                    }),

                TextColumn::make('product_id')
                    ->label('Product')
                    ->formatStateUsing(fn ($state) => optional(Product::find($state))->name)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('page_id')
                    ->label('Page')
                    ->formatStateUsing(fn ($state) => optional(Page::find($state))->title)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('order')
                    ->label('Display Order')
                    ->sortable()
                    ->getStateUsing(fn ($record) => (int) $record->order),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
