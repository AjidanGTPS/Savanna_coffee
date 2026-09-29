<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SavannaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ── Pengguna ─────────────────────────────────────────────
        DB::table('pengguna')->insert([
            ['nama' => 'Admin Savana',   'email' => 'admin@savana.test',   'kata_sandi' => Hash::make('password'), 'peran' => 'admin',   'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['nama' => 'Kasir 1',        'email' => 'kasir@savana.test',   'kata_sandi' => Hash::make('password'), 'peran' => 'kasir',   'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['nama' => 'Pelayan 1',      'email' => 'pelayan@savana.test', 'kata_sandi' => Hash::make('password'), 'peran' => 'pelayan', 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['nama' => 'Barista 1',      'email' => 'barista@savana.test', 'kata_sandi' => Hash::make('password'), 'peran' => 'barista', 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['nama' => 'Dapur 1',        'email' => 'dapur@savana.test',   'kata_sandi' => Hash::make('password'), 'peran' => 'dapur',   'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
        ]);

        // ── Meja ─────────────────────────────────────────────────
        $meja = [];
        $areas = ['Indoor', 'Indoor', 'Indoor', 'Indoor', 'Outdoor', 'Outdoor', 'Outdoor', 'Outdoor', 'VIP', 'VIP'];
        for ($i = 1; $i <= 10; $i++) {
            $meja[] = [
                'nomor'  => str_pad($i, 2, '0', STR_PAD_LEFT),
                'nama'   => 'Meja ' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'barcode' => Str::random(20),
                'kapasitas' => in_array($areas[$i - 1], ['VIP']) ? 6 : 4,
                'area'   => $areas[$i - 1],
                'status' => 'kosong',
                'aktif'  => 1,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ];
        }
        DB::table('meja')->insert($meja);

        // ── Kategori (parent + child) ─────────────────────────────
        DB::table('kategori')->insert([
            // Parent
            ['id' => 1, 'induk_id' => null, 'nama' => 'Minuman', 'slug' => 'minuman', 'urutan' => 1, 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id' => 2, 'induk_id' => null, 'nama' => 'Makanan', 'slug' => 'makanan', 'urutan' => 2, 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            // Children of Minuman
            ['id' => 3, 'induk_id' => 1, 'nama' => 'Kopi',        'slug' => 'kopi',        'urutan' => 1, 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id' => 4, 'induk_id' => 1, 'nama' => 'Non-Kopi',    'slug' => 'non-kopi',    'urutan' => 2, 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id' => 5, 'induk_id' => 1, 'nama' => 'Jus & Soda',  'slug' => 'jus-soda',    'urutan' => 3, 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            // Children of Makanan
            ['id' => 6, 'induk_id' => 2, 'nama' => 'Snack',       'slug' => 'snack',       'urutan' => 1, 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id' => 7, 'induk_id' => 2, 'nama' => 'Makanan Berat','slug' => 'makanan-berat','urutan' => 2, 'aktif' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
        ]);

        // ── Grup Opsi ─────────────────────────────────────────────
        DB::table('grup_opsi')->insert([
            ['id' => 1, 'nama' => 'Suhu',          'slug' => 'suhu',          'tipe' => 'tunggal', 'wajib' => 1, 'urutan' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id' => 2, 'nama' => 'Tingkat Manis', 'slug' => 'tingkat-manis', 'tipe' => 'tunggal', 'wajib' => 0, 'urutan' => 2, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['id' => 3, 'nama' => 'Tambahan Topping', 'slug' => 'topping',    'tipe' => 'ganda',   'wajib' => 0, 'urutan' => 3, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
        ]);

        DB::table('opsi')->insert([
            // Suhu
            ['grup_opsi_id' => 1, 'nama' => 'Panas',  'harga_tambahan' => 0,    'bawaan' => 1, 'tersedia' => 1, 'urutan' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['grup_opsi_id' => 1, 'nama' => 'Dingin', 'harga_tambahan' => 0,    'bawaan' => 0, 'tersedia' => 1, 'urutan' => 2, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            // Tingkat Manis
            ['grup_opsi_id' => 2, 'nama' => 'Normal', 'harga_tambahan' => 0,    'bawaan' => 1, 'tersedia' => 1, 'urutan' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['grup_opsi_id' => 2, 'nama' => 'Kurang', 'harga_tambahan' => 0,    'bawaan' => 0, 'tersedia' => 1, 'urutan' => 2, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['grup_opsi_id' => 2, 'nama' => 'Ekstra', 'harga_tambahan' => 0,    'bawaan' => 0, 'tersedia' => 1, 'urutan' => 3, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            // Topping
            ['grup_opsi_id' => 3, 'nama' => 'Whipped Cream', 'harga_tambahan' => 5000,  'bawaan' => 0, 'tersedia' => 1, 'urutan' => 1, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['grup_opsi_id' => 3, 'nama' => 'Keju',          'harga_tambahan' => 5000,  'bawaan' => 0, 'tersedia' => 1, 'urutan' => 2, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['grup_opsi_id' => 3, 'nama' => 'Pearl',         'harga_tambahan' => 3000,  'bawaan' => 0, 'tersedia' => 1, 'urutan' => 3, 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
        ]);

        // ── Produk ────────────────────────────────────────────────
        $produkData = [
            // Kopi (kategori 3) - dengan varian
            ['kategori_id' => 3, 'nama' => 'Espresso',         'slug' => 'espresso',       'deskripsi' => 'Shot espresso murni, bold dan kuat.', 'harga_dasar' => 0,     'punya_varian' => 1, 'urutan' => 1],
            ['kategori_id' => 3, 'nama' => 'Americano',        'slug' => 'americano',       'deskripsi' => 'Espresso dengan tambahan air panas.', 'harga_dasar' => 0,     'punya_varian' => 1, 'urutan' => 2],
            ['kategori_id' => 3, 'nama' => 'Cappuccino',       'slug' => 'cappuccino',      'deskripsi' => 'Espresso dengan susu steam dan foam.', 'harga_dasar' => 0,     'punya_varian' => 1, 'urutan' => 3],
            ['kategori_id' => 3, 'nama' => 'Latte',            'slug' => 'latte',           'deskripsi' => 'Espresso dengan susu steamed yang creamy.', 'harga_dasar' => 0,'punya_varian' => 1, 'urutan' => 4],
            ['kategori_id' => 3, 'nama' => 'Kopi Susu Savana', 'slug' => 'kopi-susu-savana','deskripsi' => 'Signature kopi susu ala SAVANA.', 'harga_dasar' => 0,          'punya_varian' => 1, 'urutan' => 5],
            // Non-Kopi (kategori 4) - harga dasar
            ['kategori_id' => 4, 'nama' => 'Matcha Latte',     'slug' => 'matcha-latte',    'deskripsi' => 'Matcha premium dengan susu segar.', 'harga_dasar' => 35000,   'punya_varian' => 0, 'urutan' => 1],
            ['kategori_id' => 4, 'nama' => 'Chocolate',        'slug' => 'chocolate',       'deskripsi' => 'Cokelat belgia yang kaya rasa.', 'harga_dasar' => 32000,       'punya_varian' => 0, 'urutan' => 2],
            ['kategori_id' => 4, 'nama' => 'Teh Tarik',        'slug' => 'teh-tarik',       'deskripsi' => 'Teh dengan susu creamy, segar.', 'harga_dasar' => 25000,       'punya_varian' => 0, 'urutan' => 3],
            // Jus & Soda (kategori 5)
            ['kategori_id' => 5, 'nama' => 'Lemon Soda',       'slug' => 'lemon-soda',      'deskripsi' => 'Soda segar dengan perasan lemon.', 'harga_dasar' => 25000,     'punya_varian' => 0, 'urutan' => 1],
            ['kategori_id' => 5, 'nama' => 'Jus Alpukat',      'slug' => 'jus-alpukat',     'deskripsi' => 'Alpukat segar blended creamy.', 'harga_dasar' => 30000,        'punya_varian' => 0, 'urutan' => 2],
            // Snack (kategori 6)
            ['kategori_id' => 6, 'nama' => 'Croissant',        'slug' => 'croissant',       'deskripsi' => 'Croissant buttery yang renyah.', 'harga_dasar' => 28000,       'punya_varian' => 0, 'urutan' => 1],
            ['kategori_id' => 6, 'nama' => 'Roti Bakar',       'slug' => 'roti-bakar',      'deskripsi' => 'Roti bakar dengan pilihan topping.', 'harga_dasar' => 20000,   'punya_varian' => 0, 'urutan' => 2],
            // Makanan Berat (kategori 7)
            ['kategori_id' => 7, 'nama' => 'Nasi Goreng Savana','slug' => 'nasi-goreng-savana','deskripsi' => 'Nasi goreng spesial dengan telur mata sapi.', 'harga_dasar' => 40000, 'punya_varian' => 0, 'urutan' => 1],
            ['kategori_id' => 7, 'nama' => 'Spaghetti Bolognese','slug' => 'spaghetti-bolognese','deskripsi' => 'Pasta dengan saus daging sapi.', 'harga_dasar' => 45000, 'punya_varian' => 0, 'urutan' => 2],
        ];

        foreach ($produkData as $p) {
            DB::table('produk')->insert(array_merge($p, [
                'tersedia' => 1,
                'dibuat_pada' => $now,
                'diperbarui_pada' => $now,
            ]));
        }

        // ── Varian untuk produk berkuran ──────────────────────────
        $varianKopi = [
            ['Short (180ml)', 28000],
            ['Tall (300ml)',  35000],
            ['Grande (450ml)', 42000],
        ];

        // Produk kopi: id 1-5
        for ($produkId = 1; $produkId <= 5; $produkId++) {
            foreach ($varianKopi as $i => [$nama, $harga]) {
                DB::table('varian_produk')->insert([
                    'produk_id' => $produkId,
                    'nama' => $nama,
                    'harga' => $harga,
                    'urutan' => $i + 1,
                    'tersedia' => 1,
                    'dibuat_pada' => $now,
                    'diperbarui_pada' => $now,
                ]);
            }
        }

        // ── Asosiasi grup opsi ke produk (semua kopi & non-kopi pakai suhu + manis + topping) ──
        $coffeeIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // semua minuman
        foreach ($coffeeIds as $pId) {
            DB::table('produk_grup_opsi')->insert([
                ['produk_id' => $pId, 'grup_opsi_id' => 1, 'urutan' => 1], // suhu
                ['produk_id' => $pId, 'grup_opsi_id' => 2, 'urutan' => 2], // manis
                ['produk_id' => $pId, 'grup_opsi_id' => 3, 'urutan' => 3], // topping
            ]);
        }

        // ── Pengaturan ────────────────────────────────────────────
        DB::table('pengaturan')->insert([
            ['kunci' => 'persen_pajak',    'nilai' => '10', 'deskripsi' => 'Pajak dalam persen (%)', 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['kunci' => 'persen_layanan',  'nilai' => '0',  'deskripsi' => 'Biaya layanan dalam persen (%)', 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['kunci' => 'nama_toko',       'nilai' => 'SAVANA Coffee', 'deskripsi' => 'Nama toko', 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
            ['kunci' => 'alamat_toko',     'nilai' => 'Jl. Kopi Savana No. 1', 'deskripsi' => 'Alamat toko', 'dibuat_pada' => $now, 'diperbarui_pada' => $now],
        ]);
    }
}
