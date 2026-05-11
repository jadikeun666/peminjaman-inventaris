<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'nama_barang'   => 'Proyektor Epson',
                'kode_barang'   => 'PRY-001',
                'jumlah_barang' => 3,
                'harga_sewa'    => 50000,
                'foto'          => 'https://els.id/wp-content/uploads/2024/10/Epson-EB-X600.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_barang'   => 'Microphone TOA',
                'kode_barang'   => 'MIC-001',
                'jumlah_barang' => 5,
                'harga_sewa'    => 25000,
                'foto'          => 'https://toa.co.id/document/15028-zm-520_new_front-picture.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_barang'   => 'Kamera DSLR Canon',
                'kode_barang'   => 'KMR-001',
                'jumlah_barang' => 2,
                'harga_sewa'    => 75000,
                'foto'          => 'https://www.static-src.com/wcsstore/Indraprastha/images/catalog/full//79/MTA-2211810/canon_canon-eos-3000d-kit-18-55mm-iii-kamera-dslr---black_full03.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_barang'   => 'Speaker Aktif JBL',
                'kode_barang'   => 'SPK-001',
                'jumlah_barang' => 4,
                'harga_sewa'    => 40000,
                'foto'          => 'https://i5.walmartimages.com/seo/JBL-Partybox-110-Portable-party-speaker-with-160W-powerful-sound-built-in-lights-and-splashproof-design-Black_4c7056a8-8497-4dab-b2d7-f8f1204c7338.303f70e7970eed00ac6739a7c3658594.jpeg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_barang'   => 'Lampu Sorot LED',
                'kode_barang'   => 'LMP-001',
                'jumlah_barang' => 0,
                'harga_sewa'    => 30000,
                'foto'          => 'https://cdn.ruparupa.io/fit-in/400x400/filters:format(webp)/filters:quality(90)/ruparupa-com/image/upload/Products/10605717_1.jpg',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],

        ]);
    }
}