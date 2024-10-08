<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NewsResource\Pages;
use App\Filament\Admin\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $activeNavigationIcon = 'heroicon-s-newspaper';

    protected static ?string $navigationGroup = 'Instituição';

    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Notícias';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados da Notícia')->schema([
                    SpatieMediaLibraryFileUpload::make('banner')
                        ->columnSpanFull()
                        ->collection('banners')
                        ->imageEditor()
                        ->required()
                        ->multiple()
                        ->hiddenLabel(),
                    Group::make()->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required(),
                        TextInput::make('subtitle')
                            ->label('Subtítulo'),
                    ])->columns(2),
                    Textarea::make('description')
                        ->label('Descrição')
                        ->rows(5)
                        ->required(),
                    Hidden::make('created_by')
                        ->default(auth()->id()),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('banner')
                    ->label('Imagem')
                    ->collection('banners'),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('subtitle')
                    ->label('Subtítulo'),
                TextColumn::make('User.name')
                    ->label('Publicado por'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
