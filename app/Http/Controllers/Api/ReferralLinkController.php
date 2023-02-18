<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReferralLink\VerifyFriendRequest;
use App\Models\ReferralLink;
use App\Models\User;

class ReferralLinkController extends Controller
{
    public function my_codes(){
        $referral_links = ReferralLink::
            where(['user_id' => auth()->user()->id])
            ->with([
                'users' => function($q){
                    $q->select(['id', 'first_name', 'last_name', 'email', 'referral_link_code', 'verified']);
                }
            ])
            ->get();

        return response()->json([
            "status" => true,
            "data" => $referral_links,
        ]);
    }

    public function verify_friend(VerifyFriendRequest $request){
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
