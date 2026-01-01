<?php

namespace App\Imports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SantriImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Simple updateOrCreate logic to avoid duplicates based on NIS
        return Santri::updateOrCreate(
            ['nis' => $row['nis']],
            [
                'nama' => $row['nama'],
                'tanggal_lahir' => $this->transformDate($row['tanggal_lahir']),
                'alamat' => $row['alamat'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'nama_orang_tua' => $row['nama_orang_tua'],
                'status' => 'aktif', // Default active
            ]
        );
    }

    public function rules(): array
    {
        return [
            'nis' => 'required',
            'nama' => 'required',
        ];
    }

    private function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
