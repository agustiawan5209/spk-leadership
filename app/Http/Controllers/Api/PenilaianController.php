<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\AspekKriteria;
use App\Models\DataPenilaian;
use App\Models\KategoriPenilaian;
use App\Models\KriteriaPenilaian;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StorePenilaianRequest;

class PenilaianController extends Controller
{
    public function create()
    {
        $valid = Validator::make(Request::all(), [
            'kategori' => 'required|exists:kategori_penilaians,id',
        ]);
        if ($valid->fails()) {
            return redirect()->route('Penilaian.index')->with('message', 'Evaluasi Harus Di Pilih');
        }


        // dd($alternatif);
        return response()->json([
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


}
