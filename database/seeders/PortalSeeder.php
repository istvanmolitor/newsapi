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
            ],
            [
                'name' => 'telex',
                'domain' => 'https://telex.hu',
            ],
            [
                'name' => '444',
                'domain' => 'https://444.hu',
            ],
        ];

        foreach ($portals as $data) {
            Portal::updateOrCreate(
                [
                    'name' => $data['name'],
                ],
                [
                    'domain' => $data['domain'],
                ]
            );
        }
    }
}
