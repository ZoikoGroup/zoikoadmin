<?php
namespace App\Filament\Resources\CartResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class CartItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'cartItems';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('product.name')->label('Product'),
            TextColumn::make('quantity'),
        ]);
    }
}
