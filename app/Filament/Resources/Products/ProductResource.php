<?php

namespace App\Filament\Resources\Products;

use App\Models\Product;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, EditAction};
use Filament\Forms\Components\{DatePicker, FileUpload, Hidden, KeyValue, Repeater, RichEditor, Select, TagsInput, Textarea, TextInput, Toggle};
use Filament\Resources\Resource;
use Filament\Schemas\Components\{Grid, Section, Tabs, Tabs\Tab};
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn, ToggleColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Products · ผลิตภัณฑ์';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $modelLabel = 'product';
    protected static ?string $pluralModelLabel = 'Products';
    protected static ?string $recordTitleAttribute = 'name_th';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Product')->schema([
                Grid::make(4)->schema([
                    Select::make('group')->options(\App\Models\Product::GROUPS)->required(),
                    Select::make('form')->options(\App\Models\Product::FORMS)->required(),
                    TextInput::make('symbol')->label('Symbol on the table (2 letters)')->maxLength(4)->required(),
                    Toggle::make('is_published')->label('Show on the website')->default(true)->inline(false),
                ]),
                FileUpload::make('image')->label('Pack shot for the table (transparent PNG or WebP)')->image()->disk('public')->directory('site/products')->visibility('public')->required(),
                FileUpload::make('photos')->label('Product page photos')->image()->multiple()->reorderable()->appendFiles()->disk('public')->directory('site/products')->visibility('public'),
            ]),
            Tabs::make('Language')->tabs([
                Tab::make('ไทย')->schema([
                    TextInput::make('name_th')->label('ชื่อสินค้า')->required(),
                    TextInput::make('slug_th')->label('ชื่อในลิงก์ (เช่น ไฟเบอร์มะขาม)')->required()->helperText('Changing this changes the page address. Add the old one under Redirects.'),
                    Textarea::make('desc_th')->label('คำอธิบาย')->rows(4),
                    TagsInput::make('ingredients_th')->label('ส่วนผสม (กด Enter หลังแต่ละรายการ)'),
                    TextInput::make('seo_title_th')->label('ชื่อหน้าใน Google (ถ้าเว้นว่างจะใช้ชื่อสินค้า)')->maxLength(60),
                    Textarea::make('seo_description_th')->label('คำอธิบายใน Google')->rows(2)->maxLength(160),
                ]),
                Tab::make('English')->schema([
                    TextInput::make('name_en')->label('Name')->required(),
                    TextInput::make('slug_en')->label('Address name (e.g. tamarind-fiber)')->required()->alphaDash(),
                    Textarea::make('desc_en')->label('Description')->rows(4),
                    TagsInput::make('ingredients_en')->label('Ingredients (press Enter after each)'),
                    TextInput::make('seo_title_en')->label('Title in Google (empty uses the name)')->maxLength(60),
                    Textarea::make('seo_description_en')->label('Description in Google')->rows(2)->maxLength(160),
                ]),
            ]),
            Section::make('Badges')->schema([
                Select::make('badges')->multiple()->options(array_combine($b = ['high fiber', 'low calorie', 'no preservatives', 'sugar free', 'fat free', 'vegetarian', 'high vitamin C', 'GHP', 'HACCP'], $b)),
            ]),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->disk('public')->height(48),
                TextColumn::make('id')->label('No.')->sortable(),
                TextColumn::make('name_th')->label('ไทย')->searchable(),
                TextColumn::make('name_en')->label('English')->searchable(),
                TextColumn::make('group')->badge(),
                TextColumn::make('form')->badge()->color('gray'),
                ToggleColumn::make('is_published')->label('On site'),
            ])
            ->filters([SelectFilter::make('group')->options(\App\Models\Product::GROUPS), SelectFilter::make('form')->options(\App\Models\Product::FORMS)])
            ->recordActions([Action::make('view')->label('View')->icon('heroicon-o-arrow-top-right-on-square')->url(fn ($record) => $record->url('th'))->openUrlInNewTab(), EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])->defaultSort('sort')->reorderable('sort');
    }

    public static function canCreate(): bool { return true; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
