<?php

namespace Database\Seeders;

use App\Models\AspekKriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AspekKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aspek_kriterias = array(
            array(
                "id" => 1,
                "nama" => "Kriteria Penilaian",
                "persentase" => 100,
                "bobot" => 10,
                "core_factory" => 60,
                "secondary_factory" => 40,
                "created_at" => "2024-08-09 19:53:54",
                "updated_at" => "2024-08-10 01:33:42",
            ),
            array(
                "id" => 2,
                "nama" => "Sikap Kerja",
                "persentase" => 40,
                "bobot" => 4,
                "core_factory" => 60,
                "secondary_factory" => 40,
                "created_at" => "2024-10-04 13:45:16",
                "updated_at" => "2024-10-04 13:45:16",
            ),
            array(
                "id" => 3,
                "nama" => "Kepribadian",
                "persentase" => 30,
                "bobot" => 3,
                "core_factory" => 60,
                "secondary_factory" => 40,
                "created_at" => "2024-10-04 13:46:20",
                "updated_at" => "2024-10-04 13:46:20",
            ),
            array(
                "id" => 4,
                "nama" => "Tanggung Jawab",
                "persentase" => 30,
                "bobot" => 3,
                "core_factory" => 60,
                "secondary_factory" => 40,
                "created_at" => "2024-10-04 13:46:43",
                "updated_at" => "2024-10-04 13:46:43",
            ),
        );

        $AspekKriteria = AspekKriteria::insert($aspek_kriterias);
    }
}
