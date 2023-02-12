<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialities = [
            ['name' => "Acil tıp"],
            ['name' => "Anestezi"],
            ['name' => "Göğüs cerrahisi"],
            ['name' => "Pediatri"],
            ['name' => "Genel cerrahi"],
            ['name' => "Ortopedi"],
            ['name' => "KVC"],
            ['name' => "Plastik cerrahi"],
            ['name' => "Üroloji"],
            ['name' => "KBB"],
            ['name' => "Enfeksiyon hastalıkları"],
            ['name' => "Kadın Doğum"],
            ['name' => "Çocuk Cerrahisi"],
            ['name' => "Beyin Cerrahisi"],
            ['name' => "Kardiyoloji"],
            ['name' => "Göz Hast."],
            ['name' => "Göğüs Hast."],
            ['name' => "Dahiliye"],
            ['name' => "Radyoloji"],
            ['name' => "Dermatoloji"],
            ['name' => "Adli Tıp"],
            ['name' => "Halk Sağlığı"],
            ['name' => "Nöroloji"],
            ['name' => "FTR"],
            ['name' => "Psikiyatri"],
            ['name' => "Pratisyen"],
        ];
        Specialty::insert($specialities);
    }
}
