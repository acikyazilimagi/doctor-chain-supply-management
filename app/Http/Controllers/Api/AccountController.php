<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateRequest;
use App\Models\User;

class AccountController extends Controller
{
    public function show(){
        $user = User::
            select(['name', 'email', 'specialty', 'email_verified_at'])
            ->where(['id' => auth()->user()->id])
            ->with([
                'specialty' => function($q){
                    $q->select(['id', 'name']);
                }
            ])
            ->first();

        return response()->json([
            "status" => true ,
            "data" => $user,
        ]);
    }

    public function update(UpdateRequest $request){
        try {
            $user = auth()->user();
            $user->name = $request->get('name');
            $user->specialty = $request->get('specialty');
            $user->save();

            return response()->json([
                'status' => true,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Kayıt başarıyla güncellendi.",
                    "type" => "success",
                ]
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                "message" => [
                    "title" => "Hata !",
                    'body' => 'Teknik bir sorun oluştu, işlem başarısız oldu.',
                    "type" => "error",
                ]
            ], 500);
        }
    }
}
