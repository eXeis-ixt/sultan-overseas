<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\PassportResource\Pages;
use App\Filament\Resources\PassportResource\RelationManagers;
use App\Models\Client;
use App\Models\Passport;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PassportResource extends Resource
{
    protected static ?string $model = Passport::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Step::make('General Info')->schema([
                        Section::make('Passport Status')->schema([

                            Section::make('Status')->schema([
                                ToggleButtons::make('status')
                                ->inline()
                                ->options(OrderStatus::class)
                                ->required(),
                            ]),

                        ]),

                        Section::make('Passport Info')->schema([
                            TextInput::make('sl')
                            ->default('SL-' . random_int(1000, 9999))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(32)
                            ->unique(Passport::class, 'sl', ignoreRecord: true),

                            TextInput::make('name')->required(),

                            TextInput::make('passport_number')->required(),
                            Select::make('client_id')
                            ->preload()
                            ->searchable()
                            ->relationship('client', 'name')
                            ->label('Client (provider) ')
                            ->required()


                        ])->columns(2),

                    ]),

                    Step::make('Balance and due')->schema([
                       Section::make('Balance')->schema([

                           TextInput::make('due')
                           ->numeric()
                           ->required()
                           ->default(0),
                           TextInput::make('total')->label('Total cost of service')->numeric()->required()->default(0),
                       ])->columns(2),
                    ]),

                    Step::make('Dates Info')->schema([

                        Section::make('Embassy and Delivery')->schema([

                            DatePicker::make('embassy_date'),

                            DatePicker::make('delivery_date'),
                        ])->columns(2),

                    ]),

                    Step::make('Images (optional)')->schema([

                        Section::make('Images')->schema([
                            FileUpload::make('image')->image()->imageEditor()->disk('public')

                        ])->columnSpanFull(),
                    ])

                ])->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sl')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('passport_number')->searchable()->sortable(),
                TextColumn::make('client.name')->searchable()->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('due')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('embassy_date')->sortable()->toggleable(isToggledHiddenByDefault: true),


            ])
            ->filters([
                SelectFilter::make('status')->options(OrderStatus::class)
                ->label('Current passport status')
                ->multiple()
                ,
                SelectFilter::make('client_id')->relationship('client', 'name')->label('Client (provider)')->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ExportAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exports([
                    ExcelExport::make()->withColumns([
                        Column::make('sl'),
                        Column::make('name'),
                        Column::make('passport_number'),
                        Column::make('client.name'),
                        Column::make('status'),
                        Column::make('due'),
                        Column::make('total'),
                        Column::make('embassy_date'),
                    ])
                ]),
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
            'index' => Pages\ListPassports::route('/'),
            'create' => Pages\CreatePassport::route('/create'),
            'edit' => Pages\EditPassport::route('/{record}/edit'),
        ];
    }
}
