<?php

namespace App\Http\Controllers\Api;

use App\Events\ProfileUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateRequest;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

            event(new ProfileUpdatedEvent($user));

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

    public function password_reset(PasswordResetRequest $request) {
        try {
            $user = User::where('email', auth()->user()->email)->first();

            if (!$user) {
                return response()->json([
                    "status" => false ,
                    "message" => [
                        "title" => "Hata",
                        "body" => "Kullanıcı bulunamadı!",
                        "type" => "error",
                    ]
                ]);
            }

            if(!Hash::check($request->get('old_password'), $user->password)){
                return response()->json([
                    "status" => false ,
                    "message" => [
                        "title" => "Hata",
                        "body" => "Eski parolanızı yanlış girdiniz.",
                        "type" => "error",
                    ]
                ]);
            }

            $user->password = bcrypt($request->get('password'));
            $user->save();

            return response()->json([
                'status' => true,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Kullanıcı şifresi başarıyla değiştirildi.",
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
