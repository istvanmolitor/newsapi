<?php

namespace Database\Seeders;

use App\Models\Portal;
use Illuminate\Database\Seeder;

class PortalSeeder extends Seeder
{
    /**
     * Seed the portals table.
     */
    public function run(): void
    {
        $portals = [
            [
                'name' => 'index',
                'domain' => 'https://index.hu',
                'rss' => 'https://index.hu/24ora/rss/',
            ],
            [
                'name' => 'telex',
                'domain' => 'https://telex.hu',
                'rss' => 'https://telex.hu/rss',
            ],
            [
                'name' => '444',
                'domain' => 'https://444.hu',
                'rss' => 'https://444.hu/feed',
            ],
        ];

        foreach ($portals as $data) {
            Portal::updateOrCreate(
                [
                    'name' => $data['name'],
                ],
                [
                    'domain' => $data['domain'],
                    'rss' => $data['rss'],
                ]
            );
        }
    }
}
