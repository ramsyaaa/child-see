<?php

namespace Database\Seeders;

use App\Models\SiteSettings;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'site_name'        => 'Child See',
            'site_tagline'     => 'Identifikasi Anak Berkebutuhan Khusus Sekolah Dasar',
            'site_email'       => 'halo@childsee.id',
            'site_phone'       => '+62 812-3456-7890',
            'site_address'     => 'Bandung, Jawa Barat, Indonesia',
            'site_instagram'   => 'https://instagram.com/childsee.id',
            'site_facebook'    => '',
            'site_whatsapp'    => 'https://wa.me/6281234567890',
            'site_youtube'     => '',
            'site_logo'        => '',
            'seo_title'        => 'Child See — Identifikasi Dini Anak Berkebutuhan Khusus',
            'seo_description'  => 'Child See membantu guru dan orang tua mengidentifikasi hambatan belajar anak SD secara dini dengan instrumen observasi terstandar.',
            'seo_keywords'     => 'identifikasi ABK, anak berkebutuhan khusus, hambatan belajar, inklusi, sekolah dasar, child see',
            'og_image'         => '',
        ];

        foreach ($defaults as $key => $value) {
            SiteSettings::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
