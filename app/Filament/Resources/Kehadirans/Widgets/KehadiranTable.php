<?php

namespace App\Filament\Resources\Kehadirans\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Kehadiran;
use Dom\Text;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class KehadiranTable extends TableWidget
{
    public ?int $kegiatanId = null;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Daftar Kehadiran';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Kehadiran::query()
                    ->where('kegiatan_id', $this->kegiatanId)
                    ->with(['pegawai.opd'])
            )
            ->columns([
                TextColumn::make('pegawai.nama')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('pegawai.nip')
                    ->label('NIP')
                    ->searchable(),
                TextColumn::make('pegawai.jabatan')
                    ->label('Jabatan'),
                TextColumn::make('opd_display')
                    ->label('OPD')
                    ->searchable()
                    ->getStateUsing(
                        fn($record) =>
                        $record->pegawai?->opd?->nama_opd
                            ?? $record->pegawai?->unit_kerja
                    ),

                TextColumn::make('status_pegawai')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn(string $state) =>
                        $state === 'internal' ? 'success' : 'warning'
                    ),
                TextColumn::make('tipe_peserta')
                    ->label('Tipe Peserta')
                    ->color(
                        fn(string $state) =>
                        $state === 'pegawai' ? 'primary' : 'secondary'
                    ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth('4xl')
                    ->mountUsing(function ($form, $record) {
                        $form->fill([
                            'status_pegawai'  => $record->status_pegawai,
                            'tipe_peserta'    => $record->tipe_peserta,
                            'latitude_hadir'  => $record->latitude_hadir,
                            'longitude_hadir' => $record->longitude_hadir,

                            'pegawai' => [
                                'nama'        => $record->pegawai?->nama,
                                'nip'         => $record->pegawai?->nip,
                                'jabatan'     => $record->pegawai?->jabatan,
                                'unit_kerja'  => $record->pegawai?->unit_kerja,
                            ],
                        ]);
                    })

                    ->form([
                        Section::make('Data Pegawai')
                            ->columns(2)
                            ->schema([
                                Select::make('status_pegawai')
                                    ->options([
                                        'internal' => 'Internal',
                                        'eksternal' => 'Eksternal',
                                    ])
                                    ->required()
                                    ->live(),
                                Select::make('tipe_peserta')
                                    ->options([
                                        'narasumber' => 'Narasumber',
                                        'peserta'    => 'Peserta',
                                    ])
                                    ->required()
                                    ->live(),
                                TextInput::make('pegawai.nama')
                                    ->label('Nama')
                                    ->dehydrated(false)
                                    ->disabled(
                                        fn($record, $get) =>
                                        $get('status_pegawai') !== 'eksternal'
                                            || $record?->pegawai?->opd_id !== null
                                    ),

                                TextInput::make('pegawai.nip')
                                    ->label('NIP')
                                    ->dehydrated(false)
                                    ->disabled(
                                        fn($record, $get) =>
                                        $get('status_pegawai') !== 'eksternal'
                                            || $record?->pegawai?->opd_id !== null
                                    ),

                                TextInput::make('pegawai.jabatan')
                                    ->label('Jabatan')
                                    ->dehydrated(false)
                                    ->disabled(
                                        fn($record, $get) =>
                                        $get('status_pegawai') !== 'eksternal'
                                            || $record?->pegawai?->opd_id !== null
                                    ),

                                TextInput::make('pegawai.unit_kerja')
                                    ->label('Unit Kerja')
                                    ->dehydrated(false)
                                    ->disabled(
                                        fn($record, $get) =>
                                        $get('status_pegawai') !== 'eksternal'
                                            || $record?->pegawai?->opd_id !== null
                                    ),

                                TextInput::make('latitude_hadir'),
                                TextInput::make('longitude_hadir'),
                            ]),
                    ])
                    ->after(function ($record, $data) {
                        $record->update([
                            'status_pegawai'  => $data['status_pegawai'],
                            'latitude_hadir'  => $data['latitude_hadir'] ?? null,
                            'longitude_hadir' => $data['longitude_hadir'] ?? null,
                        ]);

                        // update pegawai hanya eksternal tanpa OPD
                        if (
                            $record->status_pegawai === 'eksternal' &&
                            $record->pegawai?->opd_id === null &&
                            isset($data['pegawai'])
                        ) {
                            $record->pegawai->update([
                                'nama'        => $data['pegawai']['nama'] ?? $record->pegawai->nama,
                                'nip'         => $data['pegawai']['nip'] ?? $record->pegawai->nip,
                                'jabatan'     => $data['pegawai']['jabatan'] ?? $record->pegawai->jabatan,
                                'unit_kerja'  => $data['pegawai']['unit_kerja'] ?? $record->pegawai->unit_kerja,
                            ]);
                        }
                    })

                    ->label(''),
                DeleteAction::make()
                    ->label('')
                    ->requiresConfirmation()
                    ->color('danger'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
