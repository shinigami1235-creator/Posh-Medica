<?php

namespace App\Filament\Resources\Stats;

use App\Models\Stat;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class StatResource extends Resource
{
    protected static ?string $model = Stat::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Company · บริษัท';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $modelLabel = 'number';
    protected static ?string $pluralModelLabel = 'Numbers (test tubes)';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(3)->schema([
                    TextInput::make('value')->label('Number shown, e.g. 1,000+')->required(),
                    TextInput::make('fill')->label('How full the tube is (0.1 to 1)')->numeric()->minValue(0.05)->maxValue(1)->step(0.01)->default(0.7),
                    TextInput::make('color')->label('Liquid colours, light then dark')->placeholder('#f7a43a,#d9700e'),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('label_th')->label('หัวข้อ (ไทย)')->required(),
                    TextInput::make('label_en')->label('Label (English)')->required(),
                    TextInput::make('unit_th')->label('หน่วย (ไทย)'),
                    TextInput::make('unit_en')->label('Unit (English)'),
                ]),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('value'),
                TextColumn::make('label_th')->label('ไทย'),
                TextColumn::make('label_en')->label('English'),
                TextColumn::make('fill'),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])->defaultSort('sort')->reorderable('sort');
    }

    public static function canCreate(): bool { return true; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStats::route('/'),
            'create' => Pages\CreateStat::route('/create'),
            'edit' => Pages\EditStat::route('/{record}/edit'),
        ];
    }
}
