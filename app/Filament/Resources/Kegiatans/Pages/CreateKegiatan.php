<?php

namespace App\Filament\Resources\Kegiatans\Pages;

use App\Filament\Resources\Kegiatans\KegiatanResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Kehadiran;
use App\Models\Pegawai;


class CreateKegiatan extends CreateRecord
{
    protected static string $resource = KegiatanResource::class;
    // protected function afterCreate(): void
    // {
    //     $pegawaiIds = $this->data['pegawai_ids'] ?? [];

    //     foreach ($pegawaiIds as $pegawaiId) {
    //         $pegawai = Pegawai::find($pegawaiId);

    //         Kehadiran::create([
    //             'kegiatan_id'    => $this->record->id_kegiatan,
    //             'pegawai_id'     => $pegawaiId,
    //             'tipe_pegawai'   => 'pegawai',
    //             'status_pegawai' => (
    //                 $pegawai->opd_id === $this->record->opd_id
    //             ) ? 'internal' : 'eksternal',
    //             'waktu_hadir'    => null,
    //         ]);
    //     }
        
    // }
     protected function afterCreate(): void
    {
        $state = $this->form->getState();

        if (! empty($state['pegawai_ids'])) {
            $this->record
                ->pegawaiWajib()
                ->sync($state['pegawai_ids']);
        }
    
}

}