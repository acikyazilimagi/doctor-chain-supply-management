<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Epigra\TrGeoZones\Models\City;
use Epigra\TrGeoZones\Models\CityDistrict;
use Illuminate\Support\Facades\Cache;

class AddressController extends Controller
{
    public function cities(){
         $cache_key = 'address:cities';
         if (Cache::has($cache_key)){
             $cities = Cache::get($cache_key);
         }else{
             $cities = City::select('name', 'id')->get();
             Cache::forever($cache_key, $cities);
         }
        return response()->json(['data' => $cities]);
    }

    public function districts(int $city_id){
        if ($city_id > 0){
            $cache_key = 'address:districts:' . $city_id;
            if (Cache::has($cache_key)){
                $districts = Cache::get($cache_key);
            }else{
                $districts = CityDistrict::select(['id', 'ilce as name'])->where(['city_id' => $city_id])->groupBy('ilce')->get();
                Cache::set($cache_key, $districts, now()->addWeek()->toDateString());
            }
            return response()->json(['status' => true, 'data' => $districts]);
        }else{
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata !",
                    "body" => "Geçersiz bir il seçtiniz",
                    "type" => "error",
                ]
            ]);
        }
    }

    public function neighbourhoods(int $city_id, string $district_name){
        if ($city_id > 0 && strlen($district_name)){
            $cache_key = 'address:neighbourhoods:' . $city_id . ':' . $district_name;
            if (Cache::has($cache_key)){
                $districts = Cache::get($cache_key);
            }else{
                $districts = CityDistrict::select(['mahalle as name', 'id'])->where(['city_id' => $city_id, 'ilce' => $district_name])->get();
                Cache::set($cache_key, $districts, now()->addWeek()->toDateString());
            }
            return response()->json(['status' => true, 'data' => $districts]);
        }else{
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata !",
                    "body" => "Geçersiz bir il seçtiniz",
                    "type" => "error",
                ]
            ]);
        }
    }
}
