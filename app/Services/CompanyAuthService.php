<?php

namespace App\Services;

class CompanyAuthService
{
    /**
     * Dummy data yang mensimulasikan
     * data dari API authentication perusahaan.
     *
     * Nanti bagian ini akan diganti dengan
     * request ke API perusahaan yang sebenarnya.
     */
    private $employees = [
        '100001' => [
            'nik' => '100001',
            'name' => 'Admin QAD',
        ],

        '100002' => [
            'nik' => '100002',
            'name' => 'Viewer Internal',
        ],

        '100003' => [
            'nik' => '100003',
            'name' => 'Budi Internal',
        ],

        '100004' => [
            'nik' => '100004',
            'name' => 'Sinta Internal',
        ],

        '100005' => [
            'nik' => '100005',
            'name' => 'Andi Internal',
        ],
    ];

    public function findEmployeeByNik($nik)
    {
        return $this->employees[$nik] ?? null;
    }
}