<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Order Infomation')->schema([
                        Select::make('user_id')->searchable()->preload()->required()->relationship('user', 'name')->label('Customer'),
                        Select::make('peyment_method')->options([
                            'cash' => 'Cash',
                            'paypal' => 'Paypal',
                            'stripe' => 'Stripe',
                        ])->required(),

                        Select::make('payment_status')->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed',
                        ])->required()->default('pending'),

                        ToggleButtons::make('status')->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'canceled' => 'Canceled',
                        ])->required()->inline()->default('new')->colors([
                            'new' => 'info',
                            'processing' => 'warning',
                            'shipped' => 'success',
                            'delivered' => 'success',
                            'canceled' => 'danger',
                        ])->icons([
                            'new' => 'heroicon-m-sparkles',
                            'processing' => 'heroicon-m-clock',
                            'shipped' => 'heroicon-m-truck',
                            'delivered' => 'heroicon-m-check',
                            'canceled' => 'heroicon-m-x-circle',
                        ]),

                        Select::make('currency')->options([
                            'usd' => '$',
                            'eur' => '€',
                            'gbp' => '£',
                        ])->required()->default('usd'),

                        Select::make('shipping_method')->options([
                            'free' => 'Free',
                            'standard' => 'Standard',
                            'express' => 'Express',
                        ])->required()->default('free'),

                        Textarea::make('notes')->nullable()->default(null)->rows(3)->columnSpanFull(),

                    ])->columns(2),

                ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
