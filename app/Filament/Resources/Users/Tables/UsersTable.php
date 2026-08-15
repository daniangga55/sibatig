<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable()->weight('medium'),
                TextColumn::make('email')->label('Email')->searchable()->copyable(),
                TextColumn::make('role')
                    ->label('Peran')->badge()
                    ->formatStateUsing(fn (UserRole $state): string => $state->label())
                    ->color(fn (UserRole $state): string => match ($state) {
                        UserRole::SuperAdmin => 'danger',
                        UserRole::Admin => 'warning',
                        UserRole::Pimpinan => 'primary',
                        UserRole::Auditor => 'info',
                        UserRole::Viewer => 'gray',
                    }),
                IconColumn::make('is_active')->label('Aktif')->boolean()->sortable(),
                TextColumn::make('last_login_at')->label('Login terakhir')->dateTime('d M Y H:i')->placeholder('Belum pernah')->sortable(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')->label('Peran')->options(UserRole::options()),
                TernaryFilter::make('is_active')->label('Status akun')->trueLabel('Aktif')->falseLabel('Nonaktif'),
            ])
            ->defaultSort('name')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
