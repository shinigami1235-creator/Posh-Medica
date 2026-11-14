<?php

namespace App\Filament\Resources\Posts;

use App\Models\Post;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static string|\UnitEnum|null $navigationGroup = 'News · ข่าวสาร';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $modelLabel = 'post';
    protected static ?string $pluralModelLabel = 'Blog and events';
    protected static ?string $recordTitleAttribute = 'title_th';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(4)->schema([
                    Select::make('kind')->options(['event' => 'Event · กิจกรรม', 'blog' => 'Blog · บทความ'])->required()->default('event'),
                    DatePicker::make('published_on')->label('Date')->required()->default(now()),
                    TextInput::make('slug')->label('Address name')->required()->helperText('Shown in the link after the number. Thai is fine.'),
                    Toggle::make('is_published')->label('Show on the website')->default(true)->inline(false),
                ]),
                FileUpload::make('cover')->label('Cover photo (list and sharing)')->image()->imageEditor()->disk('public')->directory(fn () => 'updates/blog-and-event/' . now()->format('Y/m'))->visibility('public')->required(),
                FileUpload::make('gallery')->label('Gallery photos')->image()->multiple()->reorderable()->appendFiles()->panelLayout('grid')->disk('public')->directory(fn () => 'updates/blog-and-event/' . now()->format('Y/m'))->visibility('public'),
            ]),
            Tabs::make('Language')->tabs([
                Tab::make('ไทย')->schema([
                    TextInput::make('title_th')->label('หัวข้อ')->required(),
                    RichEditor::make('body_th')->label('เนื้อหา'),
                    Textarea::make('seo_description_th')->label('คำอธิบายใน Google (ถ้าเว้นว่างจะใช้ย่อหน้าแรก)')->rows(2)->maxLength(160),
                ]),
                Tab::make('English')->schema([
                    TextInput::make('title_en')->label('Title (empty shows the Thai)'),
                    RichEditor::make('body_en')->label('Text (empty shows the Thai)'),
                    Textarea::make('seo_description_en')->label('Description in Google')->rows(2)->maxLength(160),
                ]),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')->disk('public')->height(48),
                TextColumn::make('published_on')->label('Date')->date('d M Y')->sortable(),
                TextColumn::make('kind')->badge(),
                TextColumn::make('title_th')->label('หัวข้อ')->limit(60)->searchable(),
                ToggleColumn::make('is_published')->label('On site'),
            ])
            ->filters([SelectFilter::make('kind')->options(['event' => 'Event', 'blog' => 'Blog'])])
            ->recordActions([Action::make('view')->label('View')->icon('heroicon-o-arrow-top-right-on-square')->url(fn ($record) => $record->url('th'))->openUrlInNewTab(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])->defaultSort('published_on', 'desc');
    }

    public static function canCreate(): bool { return true; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
