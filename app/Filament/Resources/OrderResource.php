<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

                    Section::make()->schema([
                        Repeater::make('orderItems')->relationship()->required()->schema([
                            Select::make('product_id')->searchable()->preload()->required()->relationship('product', 'name')->label('Product')->disableOptionsWhenSelectedInSiblingRepeaterItems()->columnSpan(4)->reactive()->afterStateUpdated(function ($state, callable $set) {
                                $product = \App\Models\Product::find($state);
                                if ($product) {
                                    $set('unit_amount', number_format($product->price, 2));
                                    $set('total_amount', number_format($product->price, 2));
                                }
                            }),
                            TextInput::make('quantity')->required()->columnSpan(2)->default(1)->minValue(1)->numeric() ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $unitAmount = $get('unit_amount');
                                $totalAmount = $state * $unitAmount;
                                $set('total_amount', number_format($totalAmount, 2));
                            }),
                            TextInput::make('unit_amount')->required()->columnSpan(3)->default(0)->minValue(0)->numeric()->disabled()->dehydrated(),
                            TextInput::make('total_amount')->required()->columnSpan(3)->default(0)->minValue(0)->numeric(),

                        ])->columns(12)
                    ])

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
