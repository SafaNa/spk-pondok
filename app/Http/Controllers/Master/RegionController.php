<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class RegionController extends Controller
{
    public function provinces()
    {
        return response()->json(Province::orderBy('name')->pluck('name', 'code'));
    }

    public function cities(Request $request)
    {
        \Log::info('Cities request', ['province_code' => $request->province_code, 'all' => $request->all()]);

        if (!$request->province_code) {
            return response()->json(['error' => 'province_code is required'], 400);
        }

        $cities = City::where('province_code', $request->province_code)->orderBy('name')->pluck('name', 'code');

        \Log::info('Cities found', ['count' => $cities->count()]);

        return response()->json($cities);
    }

    public function districts(Request $request)
    {
        $districts = District::where('city_code', $request->city_code)->orderBy('name')->pluck('name', 'code');
        return response()->json($districts);
    }

    public function villages(Request $request)
    {
        $villages = Village::where('district_code', $request->district_code)->orderBy('name')->pluck('name', 'code');
        return response()->json($villages);
    }
}
