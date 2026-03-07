<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $transactions = [
            [
                'reference_no' => 'TRX-202603-0001',
                'transaction_date' => now()->subDays(10)->toDateString(),
                'type' => 'Payment Request',
                'description' => 'Pembayaran tagihan internet kantor pusat',
                'amount' => 2_750_000,
                'status' => 'Approved',
                'notes' => 'Jatuh tempo tanggal 25 setiap bulan.',
            ],
            [
                'reference_no' => 'TRX-202603-0002',
                'transaction_date' => now()->subDays(7)->toDateString(),
                'type' => 'Reimbursement',
                'description' => 'Reimburse biaya transport kunjungan klien',
                'amount' => 1_250_000,
                'status' => 'Pending',
                'notes' => 'Menunggu lampiran tiket dan invoice.',
            ],
            [
                'reference_no' => 'TRX-202603-0003',
                'transaction_date' => now()->subDays(3)->toDateString(),
                'type' => 'Petty Cash',
                'description' => 'Pembelian alat tulis kantor cabang Surabaya',
                'amount' => 850_000,
                'status' => 'Paid',
                'notes' => 'Sudah diverifikasi oleh finance cabang.',
            ],
            [
                'reference_no' => 'TRX-202603-0004',
                'transaction_date' => now()->subDay()->toDateString(),
                'type' => 'Vendor Payment',
                'description' => 'Pembayaran vendor maintenance AC',
                'amount' => 4_500_000,
                'status' => 'Rejected',
                'notes' => 'Dokumen kontrak belum lengkap.',
            ],
            [
                'reference_no' => 'TRX-202603-0005',
                'transaction_date' => now()->toDateString(),
                'type' => 'Transfer Bank',
                'description' => 'Transfer dana operasional ke rekening cabang',
                'amount' => 15_000_000,
                'status' => 'Draft',
                'notes' => 'Menunggu approval manajer keuangan.',
            ],
        ];

        $payload = array_map(function (array $transaction) use ($now): array {
            return [
                ...$transaction,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $transactions);

        Transaction::upsert(
            $payload,
            ['reference_no'],
            ['transaction_date', 'type', 'description', 'amount', 'status', 'notes', 'updated_at']
        );
    }
}
