<?php

namespace App\Filament\Resources\Policies;

use App\Models\Policy;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Company · บริษัท';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';
    protected static ?string $modelLabel = 'policy';
    protected static ?string $pluralModelLabel = 'Governance policies';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('slug')->label('Address name')->required()->alphaDash()->helperText('Part of /investor-relations/corporate-governance/...'),
            ]),
            Tabs::make('Language')->tabs([
                Tab::make('ไทย')->schema([
                    TextInput::make('title_th')->label('ชื่อนโยบาย')->required(),
                    RichEditor::make('body_th')->label('เนื้อหา'),
                ]),
                Tab::make('English')->schema([
                    TextInput::make('title_en')->label('Title')->required(),
                    RichEditor::make('body_en')->label('Text'),
                ]),
            ]),
            Section::make('Documents')->schema([
                Repeater::make('documents')->schema([
                    TextInput::make('title_th')->label('ชื่อเอกสาร (ไทย)')->required(),
                    TextInput::make('title_en')->label('Title (English)'),
                    FileUpload::make('file')->label('PDF')->acceptedFileTypes(['application/pdf'])->disk('public')->directory('downloads/corporate-governance')->visibility('public')->required(),
                ])->columns(3)->reorderable()->collapsible(),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title_th')->label('ไทย'),
                TextColumn::make('title_en')->label('English'),
                TextColumn::make('doc_count')->label('Documents')->getStateUsing(fn ($record) => count($record->documents ?? [])),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])->defaultSort('sort')->reorderable('sort');
    }

    public static function canCreate(): bool { return true; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'edit' => Pages\EditPolicy::route('/{record}/edit'),
        ];
    }
}
