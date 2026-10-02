<?php

namespace Database\Seeders;

use App\Models\TrHist;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TrHistSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        $users = [
            'USR001',
            'USR002',
            'USR003',
            'USR004',
            'USR005',
            'USR006',
            'USR007',
            'USR008',
            'USR009',
            'USR010',
            'USR011',
            'USR012',
            'USR013',
            'USR014',
            'USR015',
            'USR016',
            'USR017',
        ];

        $programs = [
            'poporc.p',
            'sosomt.p',
            'program01.p',
            'program02.p',
            'program03.p',
            'program04.p',
            'program05.p',
            'program06.p',
            'program07.p',
            'program08.p',
            'program09.p',
            'program10.p',
        ];

        $transTypes = [
            'ISS-SO',
            'RCT-PO',
            'ISS-WO',
        ];

        $locations = [
            'GBK',
            'GBB',
            'SMG',
            'JKT',
            'BDG',
            'SBY',
            'YGY',
        ];

        $totalRecords = 5000;

        for ($i = 1; $i <= $totalRecords; $i++) {

            /*
             * 10–15% transaksi dibuat pada hari Minggu.
             */
            if ($i <= 625) {
                $date = Carbon::create(
                    2026,
                    $faker->numberBetween(1, 9),
                    1
                );

                while ($date->dayOfWeek !== Carbon::SUNDAY) {
                    $date->addDay();

                    if ($date->month > 9) {
                        $date = Carbon::create(2026, 1, 1);
                    }
                }
            } else {
                $date = Carbon::create(
                    2026,
                    $faker->numberBetween(1, 9),
                    1
                );

                $date->day = $faker->numberBetween(1, $date->daysInMonth);
            }

            /*
             * 1–2 program dibuat lebih jarang digunakan
             * untuk simulasi program dormant.
             */
            if ($i > 4980) {
                $program = $faker->randomElement([
                    'program09.p',
                    'program10.p',
                ]);
            } else {
                $program = $faker->randomElement(array_slice($programs, 0, 10));
            }

            TrHist::create([
                'trans_number' => 'TRX' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'user' => $faker->randomElement($users),
                'trans_type' => $faker->randomElement($transTypes),
                'program' => $program,
                'trans_date' => $date->format('Y-m-d'),
                'trans_time' => $faker->time('H:i:s'),
                'location' => $faker->randomElement($locations),
            ]);
        }
    }
}