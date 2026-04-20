<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Get list of provinces (kode length 2)
     */
    public function provinces(Request $request)
    {
        $search = $request->input('search');

        $query = Wilayah::whereRaw('LENGTH(kode) = 2');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('nama')->get());
    }

    /**
     * Get list of regencies (kabupaten/kota) by province
     */
    public function regencies(Request $request, $provinceCode)
    {
        $search = $request->input('search');

        $query = Wilayah::where('kode', 'like', "{$provinceCode}.%")
            ->whereRaw('LENGTH(kode) = 5');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('nama')->get());
    }

    /**
     * Get list of districts (kecamatan) by regency
     */
    public function districts(Request $request, $regencyCode)
    {
        $search = $request->input('search');

        $query = Wilayah::where('kode', 'like', "{$regencyCode}.%")
            ->whereRaw('LENGTH(kode) = 8');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('nama')->get());
    }

    /**
     * Get list of villages (kelurahan/desa) by district
     */
    public function villages(Request $request, $districtCode)
    {
        $search = $request->input('search');

        $query = Wilayah::where('kode', 'like', "{$districtCode}.%")
            ->whereRaw('LENGTH(kode) = 13');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        return response()->json($query->orderBy('nama')->get());
    }
}
