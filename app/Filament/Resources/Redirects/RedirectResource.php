<?php

namespace App\Filament\Resources\Redirects;

use App\Models\Redirect;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Site · ระบบ';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-right';
    protected static ?string $modelLabel = 'redirect';
    protected static ?string $pluralModelLabel = 'Redirects';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('from_path')->label('Old address (starting with /)')->required()->placeholder('/th/old-page')->unique(ignoreRecord: true),
                TextInput::make('to_path')->label('New address')->required()->placeholder('/th/home'),
                Select::make('code')->options([301 => '301, moved for good', 302 => '302, moved for now'])->default(301),
            ])->columns(3),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('from_path')->searchable(),
                TextColumn::make('to_path'),
                TextColumn::make('code'),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function canCreate(): bool { return true; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }
}
