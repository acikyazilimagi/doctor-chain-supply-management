<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferralLink;

class ReferralLinkController extends Controller
{
    public function my_codes(){
        $recipes = ReferralLink::
            where(['user_id' => auth()->user()->id])
            ->with([
                'users' => function($q){
                    $q->select(['id', 'name', 'email', 'referral_link_code']);
                }
            ])
            ->get();

        return response()->json(['data' => $recipes]);
    }
}
