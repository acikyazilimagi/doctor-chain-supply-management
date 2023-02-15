<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialty;

class SpecialtyController extends Controller
{
    public function index(){
        $specialties = Specialty::select(['id', 'name'])->get();

        return response()->json([
            "status" => true,
            "data" => $specialties,
        ]);
    }
}
