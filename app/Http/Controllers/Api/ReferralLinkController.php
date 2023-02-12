<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferralLink;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralLinkController extends Controller
{
    public function my_codes(){
        $recipes = ReferralLink::
            where(['user_id' => auth()->user()->id])
            ->with([
                'users' => function($q){
                    $q->select(['id', 'name', 'email', 'referral_link_code', 'verified']);
                }
            ])
            ->get();

        return response()->json(['data' => $recipes]);
    }

    public function verify_friend(Request $request){
        $user_id = $request->get('id');
        try {
            $user = User::find($user_id);
            $user->fill([
                'verified' => true
            ])->save();

            return response()->json([
                "status" => true ,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Hesabı başarıyla onayladınız",
                    "type" => "success",
                ]
            ]);

        }catch (\Exception $exception){
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata !",
                    "body" => "İşlem başarısız oldu",
                    "type" => "error",
                ]
            ]);
        }
    }
}
