<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(){
        return auth()->user();
    }

    public function update(Request $request){
        try {
            $user = auth()->user();
            $user->specialty = $request->get('specialty');
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Kayıt başarıyla güncellendi.'
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => 'Teknik bir sorun oluştu, işlem başarısız oldu.'
            ], 500);
        }
    }
}
