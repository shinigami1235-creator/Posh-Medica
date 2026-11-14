<?php

namespace App\Filament\Resources\Awards;

use App\Models\Award;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class AwardResource extends Resource
{
    protected static ?string $model = Award::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Company · บริษัท';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $modelLabel = 'award or standard';
    protected static ?string $pluralModelLabel = 'Awards and standards';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Select::make('kind')->options(['award' => 'Award · รางวัล', 'standard' => 'Standard · มาตรฐาน'])->required(),
                TextInput::make('title_th')->label('ชื่อ (ไทย)')->required(),
                TextInput::make('title_en')->label('Name (English)')->required(),
                FileUpload::make('image')->image()->disk('public')->directory('site/awards')->visibility('public')->required(),
                Toggle::make('is_published')->label('Show on the website')->default(true),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->disk('public')->height(48),
                TextColumn::make('kind')->badge(),
                TextColumn::make('title_en')->searchable(),
                ToggleColumn::make('is_published')->label('On site'),
            ])
            ->filters([SelectFilter::make('kind')->options(['award' => 'Award', 'standard' => 'Standard'])])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])->defaultSort('sort')->reorderable('sort');
    }

    public static function canCreate(): bool { return true; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAwards::route('/'),
            'create' => Pages\CreateAward::route('/create'),
            'edit' => Pages\EditAward::route('/{record}/edit'),
        ];
    }
}
