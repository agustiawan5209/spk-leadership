<?php

namespace Database\Seeders;

use App\Models\SubKriteria;
use App\Models\AspekKriteria;
use Illuminate\Database\Seeder;
use App\Models\KriteriaPenilaian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KriteriaPenilaianSeeder extends Seeder
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

        $AspekKriteria = AspekKriteria::create($aspek_kriterias);
        $kriteria_penilaians = array(
            array(
                "id" => 1,
                "aspek_id" => 1,
                "nama" => "Loyalitas",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 3,
                "created_at" => "2024-08-09 19:55:20",
                "updated_at" => "2024-08-10 01:43:52",
            ),
            array(
                "id" => 2,
                "aspek_id" => 1,
                "nama" => "Kedisiplinan",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 3,
                "created_at" => "2024-08-09 19:55:20",
                "updated_at" => "2024-08-10 01:43:52",
            ),
            array(
                "id" => 3,
                "aspek_id" => 1,
                "nama" => "Kinerja",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 3,
                "created_at" => "2024-08-09 19:55:20",
                "updated_at" => "2024-08-10 01:43:52",
            ),
            array(
                "id" => 4,
                "aspek_id" => 1,
                "nama" => "Profesional",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 3,
                "created_at" => "2024-08-09 19:55:20",
                "updated_at" => "2024-08-10 01:43:52",
            ),
            array(
                "id" => 5,
                "aspek_id" => 1,
                "nama" => "Integritas",
                "bobot" => NULL,
                "persentase" => 40,
                "factory" => "secondary",
                "nilai_target" => 3,
                "created_at" => "2024-08-09 19:55:20",
                "updated_at" => "2024-08-10 01:43:52",
            ),
            array(
                "id" => 6,
                "aspek_id" => 1,
                "nama" => "Totalitas",
                "bobot" => NULL,
                "persentase" => 40,
                "factory" => "secondary",
                "nilai_target" => 3,
                "created_at" => "2024-08-09 19:55:20",
                "updated_at" => "2024-08-10 01:43:52",
            ),
            array(
                "id" => 7,
                "aspek_id" => 2,
                "nama" => "Kejujuran",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 5,
                "created_at" => "2024-10-04 13:48:30",
                "updated_at" => "2024-10-04 13:48:30",
            ),
            array(
                "id" => 8,
                "aspek_id" => 2,
                "nama" => "ketelitian",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 4,
                "created_at" => "2024-10-04 13:48:48",
                "updated_at" => "2024-10-04 13:48:48",
            ),
            array(
                "id" => 9,
                "aspek_id" => 2,
                "nama" => "kemampuan",
                "bobot" => NULL,
                "persentase" => 40,
                "factory" => "secondary",
                "nilai_target" => 3,
                "created_at" => "2024-10-04 13:49:05",
                "updated_at" => "2024-10-04 13:49:05",
            ),
            array(
                "id" => 10,
                "aspek_id" => 3,
                "nama" => "Kepatuhan",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 4,
                "created_at" => "2024-10-04 13:49:42",
                "updated_at" => "2024-10-04 13:49:42",
            ),
            array(
                "id" => 11,
                "aspek_id" => 3,
                "nama" => "Perilaku",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 4,
                "created_at" => "2024-10-04 13:49:56",
                "updated_at" => "2024-10-04 13:49:56",
            ),
            array(
                "id" => 12,
                "aspek_id" => 3,
                "nama" => "Penampilan",
                "bobot" => NULL,
                "persentase" => 40,
                "factory" => "secondary",
                "nilai_target" => 4,
                "created_at" => "2024-10-04 13:50:09",
                "updated_at" => "2024-10-04 13:50:09",
            ),
            array(
                "id" => 13,
                "aspek_id" => 4,
                "nama" => "Kehadiran tetap waktu",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 4,
                "created_at" => "2024-10-04 13:50:29",
                "updated_at" => "2024-10-04 13:50:29",
            ),
            array(
                "id" => 14,
                "aspek_id" => 4,
                "nama" => "Tepat waktu saat menyelesaikan tugas",
                "bobot" => NULL,
                "persentase" => 60,
                "factory" => "core",
                "nilai_target" => 4,
                "created_at" => "2024-10-04 13:50:45",
                "updated_at" => "2024-10-04 14:06:23",
            ),
            array(
                "id" => 15,
                "aspek_id" => 4,
                "nama" => "Penerimaan tugas tambahan",
                "bobot" => NULL,
                "persentase" => 40,
                "factory" => "secondary",
                "nilai_target" => 4,
                "created_at" => "2024-10-04 14:06:33",
                "updated_at" => "2024-10-04 14:06:33",
            ),
        );



        KriteriaPenilaian::insert($kriteria_penilaians);

        $no = 1;
        $sub_kriterias = [];
        for ($i = 1; $i < 7; $i++) {
            $sub_kriterias = array(
                array(
                    "id" => $no++,
                    "kriteria_id" => $i,
                    "nama" => "Tidak Memuaskan",
                    "bobot" => 1,
                    "created_at" => "2024-08-10 01:43:52",
                    "updated_at" => "2024-08-10 01:43:52",
                ),
                array(
                    "id" => $no++,
                    "kriteria_id" => $i,
                    "nama" => "Kurang Memuaskan",
                    "bobot" => 2,
                    "created_at" => "2024-08-10 01:43:52",
                    "updated_at" => "2024-08-10 01:43:52",
                ),
                array(
                    "id" => $no++,
                    "kriteria_id" => $i,
                    "nama" => "Memenuhi harapan",
                    "bobot" => 3,
                    "created_at" => "2024-08-10 01:43:52",
                    "updated_at" => "2024-08-10 01:43:52",
                ),
                array(
                    "id" => $no++,
                    "kriteria_id" => $i,
                    "nama" => "Melebihi Harapan",
                    "bobot" => 4,
                    "created_at" => "2024-08-10 01:43:52",
                    "updated_at" => "2024-08-10 01:43:52",
                ),
                array(
                    "id" => $no++,
                    "kriteria_id" => $i,
                    "nama" => "Luar Biasa",
                    "bobot" => 5,
                    "created_at" => "2024-08-10 01:43:52",
                    "updated_at" => "2024-08-10 01:43:52",
                ),
            );
            SubKriteria::insert($sub_kriterias);
        }
    }
}
