<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Penilaian;
use App\Models\KategoriPenilaian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StorePenilaianRequest;
use App\Http\Requests\UpdatePenilaianRequest;
use App\Models\Alternatif;
use App\Models\AspekKriteria;
use App\Models\DataPenilaian;
use App\Models\Keputusan;
use App\Models\KriteriaPenilaian;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use ProfileMatching;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tableName = 'kategori_penilaians'; // Ganti dengan nama tabel yang Anda inginkan
        $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
        // $columns[] = 'id';
        // $columns[] = 'nama_aspek';
        // $columns[] = 'nama';
        // $columns[] = 'nilai_target';
        // $columns[] = 'factory';

        return Inertia::render('Penilaian/Index', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'posyandus_id', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id'])),
            'data' => KategoriPenilaian::with(['alternatif', 'penilaian'])->filter(Request::only('search', 'order'))
                ->orderBy('id','desc')
                ->where('status', 'aktif')
                ->paginate(10),
            'can' => [
                'add' => Auth::user()->can('add kriteria'),
                'edit' => Auth::user()->can('edit kriteria'),
                'show' => Auth::user()->can('show kriteria'),
                'delete' => Auth::user()->can('delete kriteria'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $valid = Validator::make(Request::all(), [
            'kategori' => 'required|exists:kategori_penilaians,id',
            'alternatif' => 'required|exists:alternatifs,id',
        ]);
        if ($valid->fails()) {
            return redirect()->route('Penilaian.index')->with('message', 'Evaluasi Harus Di Pilih');
        }

        // dd(Auth::user()->staff->departement_id);
        $alternatif = Alternatif::with(['staff', 'staff.departement'])
            ->where('kategori_id', Request::input('kategori'))
            ->where('id', Request::input('alternatif'))
            ->first();

        // dd($alternatif);
        return Inertia::render('Penilaian/Form', [
            'alternatif' => $alternatif,
            'aspek' => AspekKriteria::with(['kriteriapenilaian'])->get(),
            'kriteria' => KriteriaPenilaian::with(['subkriteria'])
            ->get(),
            'aspek_kriteria' => AspekKriteria::when(Request::input('aspek_id') ?? null, function ($query, $aspek) {
                $query->where('id', $aspek);
            })->first(),
            'kategori' => KategoriPenilaian::with(['alternatif', 'alternatif.staff', 'alternatif.staff.departement'])
                ->find(Request::input('kategori')),
        ]);
    }

    public function listkaryawan()
    {
        $valid = Validator::make(Request::all(), [
            'kategori' => 'required|exists:kategori_penilaians,id',
            // 'aspek' => 'required|exists:aspek_kriterias,id',
        ]);
        if ($valid->fails()) {
            return redirect()->route('Penilaian.index')->with('message', 'Evaluasi Harus Di Pilih');
        }

        // dd(Auth::user()->staff->departement_id);
        $alternatif = Alternatif::with(['staff', 'staff.departement'])
            // ->when(Auth::user()->hasRole('Staff'), function ($query) {
            //     $query->whereHas('staff', function ($query) {
            //         $query->where('departement_id', '=', Auth::user()->staff->departement_id);
            //     });
            // })
            ->where('kategori_id', Request::input('kategori'))
            ->get();

        return Inertia::render('Penilaian/Karyawan', [
            'alternatif' => $alternatif,
            'aspek' => AspekKriteria::with(['kriteriapenilaian'])->get(),
            'kategori' => KategoriPenilaian::with(['alternatif', 'alternatif.staff', 'alternatif.staff.departement'])
                ->find(Request::input('kategori')),
            'penilaian' => Penilaian::where('staff_penilai_id', Auth::user()->staff->id)->get(),
            'staffpenilai' => Auth::user()->staff,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenilaianRequest $request)
    {
        // dd($request->all());
        $kategori_id = $request->kategori;
        $kategori = KategoriPenilaian::with(['alternatif', 'alternatif.staff', 'alternatif.staff.departement'])->find($kategori_id);
        $alternatif = Alternatif::with(['staff'])->find($request->alternatif);
        // dd($aspek);
        $staff_penilai = Staff::find(Auth::user()->staff->id);
        $tgl_penilaian = $request->has('tgl_penilaian') ? $request->tgl_penilaian : Carbon::now()->format('Y-m-d');
        // Data Matrix Penilaian Karyawan
        $data = $request->kriteria;

        for ($i = 0; $i < count($data); $i++) {
            // Pecah data karyawan
            $aspek = AspekKriteria::find($data[$i]['aspek']);
            $penilaian = Penilaian::create([
                'kategori_id' => $kategori->id,
                'kategori' => $kategori,
                'aspek_id' => $aspek->id,
                'aspek' => $aspek,
                'staff_penilai_id' => $staff_penilai->id,
                'staff_penilai' => $staff_penilai,
                'staff_id' => $alternatif->staff_id,
                'staff' => $alternatif->staff,
                'tgl_penilaian' => $tgl_penilaian,
            ]);

            $kriteria = $data[$i]['data'];
            $nilai = $data[$i]['kriteria'];
            for ($z = 0; $z < count($kriteria); $z++) {
                DataPenilaian::create([
                    'penilaian_id' => $penilaian->id,
                    'kriteria_id' => $kriteria[$z]['id'],
                    'kriteria' => $kriteria[$z],
                    'nilai' => $nilai[$z],
                ]);
            }
        }

        return redirect()->route('Penilaian.karyawan', ['kategori' => $kategori->id])->with('message', 'Penialaian Berhasil Di Buat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penilaian $penilaian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penilaian $penilaian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenilaianRequest $request, Penilaian $penilaian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penilaian $penilaian)
    {
        //
    }


    public function riwayat()
    {
        $tableName = 'kategori_penilaians'; // Ganti dengan nama tabel yang Anda inginkan
        $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
            // dd(Request::only('search', 'order'));
        return Inertia::render('Penilaian/Riwayat', [
            'search' =>  Request::input('search'),
            'table_colums' => array_values(array_diff($columns, ['remember_token', 'posyandus_id', 'password', 'email_verified_at', 'created_at', 'updated_at', 'user_id'])),
            'data' => KategoriPenilaian::with(['alternatif', 'penilaian'])->filter(Request::only('search', 'order'))
                ->where('status', 'aktif')
                ->paginate(10),
            'can' => [
                'add' => Auth::user()->can('add kriteria'),
                'edit' => Auth::user()->can('edit kriteria'),
                'show' => true,
                'delete' => Auth::user()->can('delete kriteria'),
            ]
        ]);
    }
    public function riwayat_show()
    {
        $kategori_id = Request::input('slug');


        if (Request::exists('aspek')) {
            $aspek_id = Request::input('aspek');
        } else {
            $aspek_id = AspekKriteria::orderBy('id', 'asc')->first()->id;
        }
        $profileMatching = new ProfileMatchingController($kategori_id, $aspek_id);
        $mtx = $profileMatching->matrixPenilai();
        $rank = $profileMatching->resultRank();


        // Nilai Total
        $aspekkriteria = AspekKriteria::all();
        $result = [];
        foreach ($aspekkriteria as $key => $value) {
            $PM = new ProfileMatchingController($kategori_id, $value->id);
            $PM->matrixPenilai();
            $result[$value->nama] = $PM->resultRank();
        }
        $ranking = $this->rankingAll($result, $aspekkriteria);

        $alternatif = Alternatif::with(['staff', 'staff.departement'])
        ->where('kategori_id', $kategori_id)
        ->get();
        return Inertia::render('Penilaian/RiwayatShow', [
            'kategori' => KategoriPenilaian::with(['alternatif', 'alternatif.staff', 'penilaian'])->find($kategori_id),
            'penilaian' => Penilaian::with(['datapenilaian'])->where('kategori_id', $kategori_id)->get(),
            'perhitungan' => $mtx,
            'rank' => $rank,
            'alternatif' => $alternatif,
            'aspek' => AspekKriteria::with(['kriteriapenilaian'])->find($aspek_id),
            'aspekkriteria' => $aspekkriteria,
            'keputusan' => Keputusan::with(['karyawan', 'kategoripenilaian'])->where('kategori_id', '=', $kategori_id)->get(),
            'hasilpenilaian' => $result,
            'ranking' => $ranking,
            'can' => [
                'add' => Auth::user()->can('add penilaian'),
                'edit' => Auth::user()->can('edit penilaian'),
                'show' => Auth::user()->can('show penilaian'),
                'delete' => Auth::user()->can('delete penilaian'),
            ]
        ]);
    }
    public function rankingAll($result, $aspek)
    {
        $karyawan = [];
        $ranking = [];

        // Loop melalui setiap aspek
        foreach ($aspek as $key => $value) {
            foreach ($result[$value->nama] as $k => $v) {
                // Mengumpulkan data karyawan dengan hasil yang dihitung berdasarkan persentase aspek
                $karyawan[$value->nama][$k] = [
                    'data' => $v['staff'],  // Simpan data staff (misalnya ID atau objek lainnya)
                    'staff' => $v['staff']['nama'],  // Nama karyawan
                    'hasil' => ($value->persentase / 100) * $v['hasil']  // Menghitung hasil berdasarkan persentase aspek
                ];
            }
        }

        // Menggabungkan nilai-nilai karyawan dari setiap aspek
        foreach ($karyawan as $aspek => $nilai_aspek) {
            foreach ($nilai_aspek as $nilai) {
                $nama_staff = $nilai['staff'];
                $hasil = $nilai['hasil'];

                // Jika staff belum ada di array ranking, tambahkan dengan hasil 0
                if (!isset($ranking[$nama_staff]['hasil'])) {
                    $ranking[$nama_staff]['hasil'] = 0;
                }

                // Tambahkan nilai hasil ke total nilai staff
                $ranking[$nama_staff]['hasil'] += $hasil;
                $ranking[$nama_staff]['data'] = $nilai['data'];
            }
        }

        // Urutkan berdasarkan nilai 'hasil' dari terbesar ke terkecil
        usort($ranking, function ($a, $b) {
            return $b['hasil'] <=> $a['hasil'];
        });
        return $ranking;
    }

}
