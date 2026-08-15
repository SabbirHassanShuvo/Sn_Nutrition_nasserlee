<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'site_title'=> 'SN Nutrition',
            'app_name'=> 'SN Nutrition',
            'admin_name'=> 'SN Nutrition Panel',
        ]);
    }
}
