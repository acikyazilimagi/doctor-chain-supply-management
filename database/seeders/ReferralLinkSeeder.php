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
            'code' => '1234567890ABCDEF',
            'count' => 5,
        ]);
    }
}
