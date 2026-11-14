<?php

namespace App\Filament\Resources\PageTexts;

use App\Models\PageText;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class PageTextResource extends Resource
{
    protected static ?string $model = PageText::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Content · เนื้อหา';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-language';
    protected static ?string $modelLabel = 'page text';
    protected static ?string $pluralModelLabel = 'Page texts';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('key')->label('Key (used in the page template)')->disabled(),
                TextInput::make('page')->disabled(),
            ])->columns(2),
            Textarea::make('th')->label('ไทย')->rows(4)->hidden(fn ($record) => $record?->is_html),
            Textarea::make('en')->label('English')->rows(4)->hidden(fn ($record) => $record?->is_html),
            RichEditor::make('th')->label('ไทย')->toolbarButtons(['bold', 'italic', 'link', 'undo', 'redo'])->visible(fn ($record) => $record?->is_html),
            RichEditor::make('en')->label('English')->toolbarButtons(['bold', 'italic', 'link', 'undo', 'redo'])->visible(fn ($record) => $record?->is_html),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page')->badge()->sortable(),
                TextColumn::make('th')->label('ไทย')->limit(60)->searchable()->html(),
                TextColumn::make('en')->label('English')->limit(60)->searchable()->html(),
                TextColumn::make('key')->toggleable(isToggledHiddenByDefault: true)->searchable(),
            ])
            ->filters([SelectFilter::make('page')->options(fn () => \App\Models\PageText::query()->distinct()->orderBy('page')->pluck('page', 'page')->all())])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageTexts::route('/'),
            'edit' => Pages\EditPageText::route('/{record}/edit'),
        ];
    }
}
