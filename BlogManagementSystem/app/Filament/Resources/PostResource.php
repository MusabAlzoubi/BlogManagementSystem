<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    // public static function form(Forms\Form $form): Forms\Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('title')->required(),
    //             Forms\Components\Textarea::make('content')->required(),
    //             Forms\Components\Select::make('status')
    //                 ->options([
    //                     'draft' => 'Draft',
    //                     'pending' => 'Pending Approval',
    //                     'published' => 'Published',
    //                 ])
    //                 ->required(),
    //             Forms\Components\Select::make('author_id')
    //                 ->relationship('author', 'name')
    //                 ->required()
    //                 ->label('Author'),
    //         ]);
    // }

    public static function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->label('Title'),
            Forms\Components\Textarea::make('content')
                ->required()
                ->label('Content'),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'pending' => 'Pending Approval',
                    'published' => 'Published',
                ])
                ->default(fn($record) => $record && $record->needsApproval() ? 'pending' : 'draft')
                ->required()
                ->label('Status')
                ->disabled(fn($record) => $record && $record->needsApproval() && $record->status === 'pending'),
            Forms\Components\Select::make('author_id')
                ->relationship('author', 'name')
                ->required()
                ->label('Author'),
        ]);
}



    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('author.name')->label('Author')->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
