<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Tematik',
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Bahasa Daerah',
            'Ilmu Pengetahuan Alam (IPA)',
            'Ilmu Pengetahuan Sosial (IPS)',
            'Pendidikan Pancasila dan Kewarganegaraan (PPKn)',
            'Pendidikan Agama dan Budi Pekerti',
            'Seni Budaya',
            'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)',
            'Prakarya dan Kewirausahaan',
            'Informatika (TIK)',
            'Fisika',
            'Kimia',
            'Biologi',
            'Ekonomi',
            'Geografi',
            'Sosiologi',
            'Sejarah Indonesia',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category],
                ['slug' => Str::slug($category)]
            );
        }
    }
}
