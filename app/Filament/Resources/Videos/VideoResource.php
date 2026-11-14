<?php

namespace App\Filament\Resources\Videos;

use App\Models\Video;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Content · เนื้อหา';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $modelLabel = 'video';
    protected static ?string $pluralModelLabel = 'YouTube videos';
    

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextInput::make('youtube_id')->label('YouTube video ID or link')->required()
                    ->helperText('Paste the link from YouTube; only the ID is kept.')
                    ->dehydrateStateUsing(fn ($state) => preg_match('~(?:v=|youtu\\.be/|embed/|shorts/)([\\w-]{11})~', $state, $m) ? $m[1] : trim($state)),
                TextInput::make('title_th')->label('ชื่อวิดีโอ (ไทย)'),
                TextInput::make('title_en')->label('Title (English)'),
                Toggle::make('is_published')->label('Show on the website')->default(true),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumb')->getStateUsing(fn ($record) => 'https://i.ytimg.com/vi/' . $record->youtube_id . '/default.jpg')->height(40),
                TextColumn::make('youtube_id'),
                TextColumn::make('title_th')->label('ไทย'),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
