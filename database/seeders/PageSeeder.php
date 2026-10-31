<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Beranda',
                'slug' => 'beranda',
                'description' => 'Halaman utama website Ansar Travel.',
                'is_published' => true,
            ],
            [
                'title' => 'Layanan',
                'slug' => 'layanan',
                'description' => 'Informasi layanan perjalanan umroh Ansar Travel.',
                'is_published' => true,
            ],
            [
                'title' => 'Jadwal',
                'slug' => 'jadwal',
                'description' => 'Informasi jadwal keberangkatan jamaah umroh.',
                'is_published' => true,
            ],
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'description' => 'Profil dan informasi tentang Ansar Travel.',
                'is_published' => true,
            ],
            [
                'title' => 'Info',
                'slug' => 'info',
                'description' => 'Berita dan informasi terbaru Ansar Travel.',
                'is_published' => true,
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'description' => 'Informasi kontak dan lokasi Ansar Travel.',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}