<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Archive;

class ArchiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $archives = [
            [
                'code' => 'ARS-001',
                'title' => 'Dokumen Pendirian Lembaga',
                'description' => 'Dokumen resmi pendirian lembaga kearsipan',
                'category' => 'Administrasi',
                'location' => 'Ruang Arsip A',
                'shelf_number' => 'A-01',
                'box_number' => 'BOX-001',
                'year' => 2020,
                'status' => 'available',
                'condition' => 'good',
                'created_by' => 1,
            ],
            [
                'code' => 'ARS-002',
                'title' => 'Laporan Keuangan Tahunan 2021',
                'description' => 'Laporan keuangan lengkap tahun 2021',
                'category' => 'Keuangan',
                'location' => 'Ruang Arsip B',
                'shelf_number' => 'B-01',
                'box_number' => 'BOX-002',
                'year' => 2021,
                'status' => 'available',
                'condition' => 'good',
                'created_by' => 1,
            ],
            [
                'code' => 'ARS-003',
                'title' => 'Surat Keputusan Direktur',
                'description' => 'Kumpulan surat keputusan direktur tahun
                2022',
                'category' => 'Kebijakan',
                'location' => 'Ruang Arsip A',
                'shelf_number' => 'A-02',
                'box_number' => 'BOX-003',
                'year' => 2022,
                'status' => 'available',
                'condition' => 'good',
                'created_by' => 1,
            ],
        ];

        foreach ($archives as $archive) {
            Archive::create($archive);
        }
    }
}
