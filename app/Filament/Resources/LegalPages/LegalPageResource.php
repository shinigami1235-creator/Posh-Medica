<?php

namespace App\Filament\Resources\LegalPages;

use App\Models\LegalPage;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class LegalPageResource extends Resource
{
    protected static ?string $model = LegalPage::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Site · ระบบ';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'legal page';
    protected static ?string $pluralModelLabel = 'Legal pages';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Language')->tabs([
                Tab::make('ไทย')->schema([
                    TextInput::make('title_th')->label('ชื่อหน้า')->required(),
                    RichEditor::make('body_th')->label('เนื้อหา'),
                ]),
                Tab::make('English')->schema([
                    TextInput::make('title_en')->label('Title')->required(),
                    RichEditor::make('body_en')->label('Text'),
                ]),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug'),
                TextColumn::make('title_th')->label('ไทย'),
                TextColumn::make('updated_at')->label('Last changed')->since(),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLegalPages::route('/'),
            'edit' => Pages\EditLegalPage::route('/{record}/edit'),
        ];
    }
}
