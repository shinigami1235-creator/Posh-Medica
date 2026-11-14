<?php

namespace App\Filament\Resources\Enquiries;

use App\Models\Enquiry;
use Filament\Actions\{Action, BulkActionGroup, DeleteAction, DeleteBulkAction, ViewAction};
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

/* Messages sent from the contact form. They are deleted automatically after config('site.enquiry_days') days. */
class EnquiryResource extends Resource
{
    protected static ?string $model = Enquiry::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Messages · ข้อความ';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $modelLabel = 'message';
    protected static ?string $pluralModelLabel = 'Contact form messages';

    public static function getNavigationBadge(): ?string
    {
        $n = Enquiry::whereNull('read_at')->count();
        return $n ? (string) $n : null;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                TextEntry::make('created_at')->label('Sent')->dateTime('d M Y H:i'),
                TextEntry::make('topic')->formatStateUsing(fn ($state) => __('posh.topics.' . $state, [], 'th') . ' / ' . __('posh.topics.' . $state, [], 'en')),
                TextEntry::make('name'),
                TextEntry::make('email')->copyable(),
                TextEntry::make('phone')->placeholder('-'),
                TextEntry::make('locale')->label('Page language'),
                TextEntry::make('message')->columnSpanFull(),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('unread')->label('')->boolean()->getStateUsing(fn ($record) => ! $record->read_at)->trueIcon('heroicon-s-envelope')->falseIcon('heroicon-o-envelope-open'),
                TextColumn::make('created_at')->label('Sent')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('topic')->badge()->formatStateUsing(fn ($state) => __('posh.topics.' . $state, [], 'th')),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('message')->limit(50),
            ])
            ->filters([
                SelectFilter::make('topic')->options(collect(\App\Http\Controllers\ContactController::TOPICS)->mapWithKeys(fn ($t) => [$t => __('posh.topics.' . $t, [], 'th')])->all()),
                TernaryFilter::make('read_at')->label('Read')->nullable(),
            ])
            ->recordActions([
                ViewAction::make()->after(fn ($record) => $record->read_at ?: $record->update(['read_at' => now()])),
                Action::make('file')->label('Attachment')->icon('heroicon-o-paper-clip')
                    ->visible(fn ($record) => filled($record->attachment))
                    ->action(fn ($record) => Storage::disk('local')->download($record->attachment)),
                DeleteAction::make()->after(fn ($record) => $record->attachment && Storage::disk('local')->delete($record->attachment)),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return ['index' => Pages\ListEnquiries::route('/')];
    }
}
