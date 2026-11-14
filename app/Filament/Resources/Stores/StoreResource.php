<?php

namespace App\Filament\Resources\Stores;

use App\Models\Store;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Company · บริษัท';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $modelLabel = 'store';
    protected static ?string $pluralModelLabel = 'Stores and partners';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('name')->required(),
                FileUpload::make('logo')->image()->disk('public')->directory('site/partners')->visibility('public')->required(),
                TextInput::make('url')->label('Link to the official shop (optional)')->url(),
                Select::make('shelf')->options([1 => 'Shelf 1', 2 => 'Shelf 2', 3 => 'Shelf 3'])->default(1)->required(),
                Toggle::make('is_published')->label('Show on the website')->default(true),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->disk('public')->height(36),
                TextColumn::make('name')->searchable(),
                TextColumn::make('shelf')->sortable(),
                IconColumn::make('has_link')->label('Link')->boolean()->getStateUsing(fn ($record) => filled($record->url)),
                ToggleColumn::make('is_published')->label('On site'),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])->defaultSort('sort')->reorderable('sort');
    }

    public static function canCreate(): bool { return true; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
