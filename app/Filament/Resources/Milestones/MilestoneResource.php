<?php

namespace App\Filament\Resources\Milestones;

use App\Models\Milestone;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class MilestoneResource extends Resource
{
    protected static ?string $model = Milestone::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Company · บริษัท';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-flag';
    protected static ?string $modelLabel = 'milestone';
    protected static ?string $pluralModelLabel = 'Milestones';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('year')->required()->maxLength(8),
                FileUpload::make('image')->image()->disk('public')->directory('site/milestones')->visibility('public'),
                Toggle::make('is_published')->label('Show on the website')->default(true),
            ]),
            Tabs::make('Language')->tabs([
                Tab::make('ไทย')->schema([
                    TextInput::make('title_th')->label('หัวข้อ')->required(),
                    Textarea::make('text_th')->label('รายละเอียด')->rows(3),
                ]),
                Tab::make('English')->schema([
                    TextInput::make('title_en')->label('Title')->required(),
                    Textarea::make('text_en')->label('Text')->rows(3),
                ]),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year')->sortable(),
                TextColumn::make('title_th')->label('ไทย'),
                TextColumn::make('title_en')->label('English'),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])->defaultSort('sort')->reorderable('sort');
    }

    public static function canCreate(): bool { return true; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMilestones::route('/'),
            'create' => Pages\CreateMilestone::route('/create'),
            'edit' => Pages\EditMilestone::route('/{record}/edit'),
        ];
    }
}
