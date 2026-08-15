<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Batch;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batches = [
            [
                'name' => 'Batch #2026-A',
                'color' => '#3b82f6', // Blue
                'status' => 'active',
            ],
            [
                'name' => 'Batch #2026-B',
                'color' => '#10b981', // Green
                'status' => 'active',
            ],
            [
                'name' => 'Batch #2026-C',
                'color' => '#f59e0b', // Yellow/Orange
                'status' => 'active',
            ],
            [
                'name' => 'Batch #2026-D',
                'color' => '#ef4444', // Red
                'status' => 'active',
            ],
        ];

        foreach ($batches as $batch) {
            Batch::updateOrCreate(
                ['name' => $batch['name']],
                $batch
            );
        }
    }
}
