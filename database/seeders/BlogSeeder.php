<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'Sortitus agnosco acidus abbas aperio...',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum scelerisque, diam vitae finibus elementum, tellus arcu dictum ex, eget imperdiet magna metus et velit. Phasellus molestie nisl ac tempor congue. Curabitur vel luctus lorem, vel finibus velit.',
                'image' => 'assets/images/banner/pngtree-drug.png', // reusing existing banner asset
                'status' => 1,
                'published_at' => '2026-06-03 07:59:31',
            ],
            [
                'title' => 'Defero voveo arbitro ambitus conatus...',
                'content' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
                'image' => 'assets/images/banner/pngtree-drug.png',
                'status' => 1,
                'published_at' => '2026-03-30 10:19:00',
            ],
            [
                'title' => 'Voluptatum viridis cubo utroque cruentus hic.',
                'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident.',
                'image' => 'assets/images/banner/pngtree-drug.png',
                'status' => 1,
                'published_at' => '2026-04-25 01:16:14',
            ],
            [
                'title' => 'Placeat copia cohibeo spes cui triumphus.',
                'content' => 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
                'image' => 'assets/images/banner/pngtree-drug.png',
                'status' => 1,
                'published_at' => '2026-04-30 03:43:19',
            ],
            [
                'title' => 'Medical Inventory Software: One System to Manage All Your Clinic Supplies',
                'content' => 'Running a healthcare facility is more than treating patients—it also requires efficient management of medical supplies, tools, and consumables. Clinics and hospitals often struggle with shortages, stockouts, or even expired inventory when relying on manual tracking methods like spreadsheets or registers.

This is where medical inventory software steps in. By providing a centralized, digital system to manage supplies, clinics can save time, reduce costs, and ensure they always have the right resources available for patient care.

In this guide, we\'ll explore why your clinic needs medical inventory software, how SN-Nutrition\'s advanced Material Tracking system simplifies the process, and why it\'s one of the best options for inventory software in India.

### Why Medical Inventory Software is Essential for Clinics
Without the right system, medical inventory can quickly become overwhelming. A single miscalculation can lead to delays in patient treatment or excessive wastage. Here\'s why clinics are increasingly adopting medical inventory software:

- **Prevents Shortages & Stockouts**: Get real-time visibility into current stock.
- **Reduces Wastage**: Track expiry dates to avoid throwing away unused medicines or materials.
- **Improves Cost Control**: Monitor supply usage to reduce overspending.
- **Saves Time for Staff**: No more manual logbooks or Excel sheets—everything is automated.
- **Enhances Patient Care**: Ensure critical supplies are always available.

### SN-Nutrition\'s Advanced Material Tracking System
At SN-Nutrition, we designed a Material Tracking system tailored for clinics and healthcare facilities. It eliminates guesswork from supply management and ensures your inventory is always under control.',
                'image' => 'assets/images/banner/pngtree-drug.png',
                'status' => 1,
                'published_at' => '2024-06-15 10:00:00',
            ]
        ];

        foreach ($blogs as $blog) {
            Blog::updateOrCreate(
                ['title' => $blog['title']],
                [
                    'slug' => Str::slug($blog['title']),
                    'content' => $blog['content'],
                    'image' => $blog['image'],
                    'status' => $blog['status'],
                    'published_at' => $blog['published_at'],
                ]
            );
        }
    }
}
