<?php

namespace Database\Seeders;

use App\Models\MasterData;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $masterData = [
            [
                'code' => 'COMP-HO',
                'name' => 'Kantor Pusat Jakarta',
                'category' => 'Company & Branch',
                'description' => 'Entitas kantor pusat untuk konsolidasi transaksi.',
                'is_active' => true,
            ],
            [
                'code' => 'BR-SBY',
                'name' => 'Cabang Surabaya',
                'category' => 'Company & Branch',
                'description' => 'Cabang operasional wilayah Jawa Timur.',
                'is_active' => true,
            ],
            [
                'code' => 'CC-FIN-HO',
                'name' => 'Finance HO',
                'category' => 'Department & Cost Center',
                'description' => 'Cost center divisi finance kantor pusat.',
                'is_active' => true,
            ],
            [
                'code' => 'BANK-BCA-00112233',
                'name' => 'Bank BCA 00112233',
                'category' => 'Cash/Bank Accounts',
                'description' => 'Rekening operasional utama perusahaan.',
                'is_active' => true,
            ],
            [
                'code' => 'VENDOR-PTMJU',
                'name' => 'PT Maju Jaya Utama',
                'category' => 'Partners (Vendor, Customer, Employee)',
                'description' => 'Vendor pengadaan alat tulis dan perlengkapan kantor.',
                'is_active' => true,
            ],
            [
                'code' => 'TRX-PR-OPS',
                'name' => 'Payment Request Operasional',
                'category' => 'Transaction Categories',
                'description' => 'Kategori transaksi pengeluaran biaya operasional.',
                'is_active' => true,
            ],
            [
                'code' => 'TAX-PPN11',
                'name' => 'PPN 11%',
                'category' => 'Tax Master',
                'description' => 'Tarif pajak pertambahan nilai sesuai ketentuan saat ini.',
                'is_active' => true,
            ],
            [
                'code' => 'DOC-PAYREQ',
                'name' => 'Payment Request',
                'category' => 'Document Types',
                'description' => 'Jenis dokumen untuk permintaan pembayaran.',
                'is_active' => true,
            ],
            [
                'code' => 'APR-MTX-001',
                'name' => 'Approval ≤ 100 Juta',
                'category' => 'Approval Matrix',
                'description' => 'Skema approval untuk nominal sampai dengan 100 juta.',
                'is_active' => true,
            ],
            [
                'code' => 'CUR-IDR',
                'name' => 'Rupiah',
                'category' => 'Currency & Exchange Rate',
                'description' => 'Mata uang dasar pembukuan perusahaan.',
                'is_active' => true,
            ],
        ];

        $payload = array_map(function (array $item) use ($now): array {
            return [
                ...$item,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $masterData);

        MasterData::upsert(
            $payload,
            ['code'],
            ['name', 'category', 'description', 'is_active', 'updated_at']
        );
    }
}
