<?php

namespace App\Filament\Resources\Kegiatans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;

use Filament\Tables;
// use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select; 
use Filament\Forms\Components\Hidden; 
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
class KehadiranRelationManager extends RelationManager
{
    protected static string $relationship = 'kehadiran';
    protected static ?string $title = 'Daftar Kehadiran';

    protected function canCreate(): bool
    {
         $user = Auth::user();
    $kegiatan = $this->getOwnerRecord();

    if ($user->role === 'operator') {
        return $kegiatan->opd_id === $user->pegawai->opd_id;
    }

    return true; // admin
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('pegawai_id')
                ->relationship('pegawai', 'nama')
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $pegawai  = \App\Models\Pegawai::find($state);
                    $kegiatan = $this->getOwnerRecord();

                    if ($pegawai && $kegiatan) {
                        $set(
                            'status_pegawai',
                            $pegawai->opd_id === $kegiatan->opd_id
                                ? 'internal'
                                : 'eksternal'
                        );
                    }
                }),

            Select::make('tipe_pegawai')
                ->options([
                    'pegawai' => 'pegawai',
                    'narasumber' => 'Narasumber',
                ])
                ->required(),

            Hidden::make('status_pegawai')->dehydrated(),

            Hidden::make('waktu_hadir')
                ->default(now())
                ->dehydrated(),

            TextInput::make('latitude_hadir')
                ->numeric()
                ->required(),

            TextInput::make('longitude_hadir')
                ->numeric()
                ->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.nama')->label('pegawai'),
                TextColumn::make('pegawai.opd.nama_opd')->label('OPD'),
                BadgeColumn::make('status_pegawai')
                    ->colors([
                        'success' => 'internal',
                        'warning' => 'eksternal',
                    ]),
                TextColumn::make('tipe_pegawai')->label('Peran'),
                TextColumn::make('waktu_hadir')->dateTime(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Kehadiran')
                    ->authorize(fn () => true),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $kegiatan = $this->getOwnerRecord();
        $pegawai  = \App\Models\Pegawai::find($data['pegawai_id']);

        if ($kegiatan->akses_kegiatan === 'satu_opd') {
            if ($pegawai->opd_id !== $kegiatan->opd_id) {
                throw new \Exception('Kegiatan ini hanya untuk satu OPD.');
            }
        }

        $data['waktu_hadir'] = now();

        return $data;
    }
}
