<?php

namespace Database\Seeders;

use App\Models\ReferralLink;
use Illuminate\Database\Seeder;

class ReferralLinkSeeder extends Seeder
{
    public function run()
    {
        ReferralLink::create([
            'user_id' => 0,
            'code' => config('referral_links.REFERRAL_LINK_GOD_CODE'),
            'count' => 5,
        ]);
    }
}
