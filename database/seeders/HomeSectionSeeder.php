<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::where('slug', 'beranda')->firstOrFail();
        PageSection::updateOrCreate(
            [
                'page_id' => $page->id,
                'section_type' => 'about',
            ],
            [
                'title' => 'Tentang Ansar Travel',
                'subtitle' => 'Mitra Perjalanan Ibadah Anda',
                'content' => [
                    'description' => 'Ansar Travel hadir untuk membantu jamaah mewujudkan perjalanan ibadah umroh dengan pelayanan yang profesional, nyaman, dan terpercaya.',
                    'description_2' => 'Kami berkomitmen memberikan pendampingan kepada jamaah sejak persiapan keberangkatan hingga pelaksanaan ibadah di Tanah Suci.',
                ],
                'image_path' => null,
                'button_text' => 'Selengkapnya',
                'button_url' => '/tentang-kami',
                'sort_order' => 3,
                'is_visible' => true,
                'status' => 'draft',
            ]
        );
    }
}