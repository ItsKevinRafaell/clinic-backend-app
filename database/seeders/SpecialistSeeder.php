<?php

namespace Database\Seeders;

use App\Models\Specialist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $specialists = [
            'Dokter Umum',
            'Dokter Gigi',
            'Dokter Kandungan',
            'Dokter Anak',
            'Dokter Bedah',
            'Dokter Penyakit Dalam',
            'Dokter Mata',
            'Dokter THT',
            'Dokter Kulit',
            'Dokter Kandungan',
            'Dokter Saraf',
        ];

        foreach ($specialists as $specialist) {
            Specialist::create([
                'name' => $specialist,
            ]);
        }
    }
}
