<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Laporan extends Seeder
{
    public function run()
    {
        $laporanData = [
            [
                'judul'          => 'Laporan Konfirmasi Permintaan Kertas A4',
                'jenis'          => 'konfirmasi',
                'status'         => 'completed',
                'konten'         => 'Permintaan kertas A4 sebanyak 20 rim telah dikonfirmasi dan siap untuk dikirim ke Divisi Administrasi.',
                'items'          => json_encode([
                    [
                        'barang_id' => 3,
                        'barang_nama' => 'Kertas A4 80gsm',
                        'barang_kode' => 'BRG003',
                        'jumlah' => 20,
                        'satuan' => 'rim',
                        'harga' => 45000,
                        'status' => 'confirmed',
                    ],
                ]),
                'dibuat_oleh'    => 'Manajer Center',
                'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-12 hours')),
                'created_at'     => date('Y-m-d H:i:s', strtotime('-12 hours')),
            ],
            [
                'judul'          => 'Laporan Pengiriman Mouse Wireless',
                'jenis'          => 'pengiriman',
                'status'         => 'completed',
                'konten'         => 'Pengiriman 10 unit mouse wireless Logitech ke Divisi Marketing telah berhasil dilakukan.',
                'items'          => json_encode([
                    [
                        'barang_id' => 2,
                        'barang_nama' => 'Mouse Wireless Logitech',
                        'barang_kode' => 'BRG002',
                        'jumlah' => 10,
                        'satuan' => 'unit',
                        'harga' => 350000,
                        'status' => 'delivered',
                    ],
                    [
                        'barang_id' => 4,
                        'barang_nama' => 'Tinta Printer Canon',
                        'barang_kode' => 'BRG004',
                        'jumlah' => 5,
                        'satuan' => 'botol',
                        'harga' => 120000,
                        'status' => 'delivered',
                    ],
                ]),
                'dibuat_oleh'    => 'Staff Gudang',
                'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'created_at'     => date('Y-m-d H:i:s', strtotime('-6 hours')),
            ],
            [
                'judul'          => 'Laporan Stok Rendah - Tinta Printer',
                'jenis'          => 'stok',
                'status'         => 'active',
                'konten'         => 'Stok tinta printer Canon sudah mencapai batas minimum. Diperlukan pengadaan segera.',
                'items'          => json_encode([
                    [
                        'barang_id' => 4,
                        'barang_nama' => 'Tinta Printer Canon',
                        'barang_kode' => 'BRG004',
                        'jumlah' => 3,
                        'satuan' => 'botol',
                        'harga' => 120000,
                        'status' => 'low_stock',
                    ],
                ]),
                'dibuat_oleh'    => 'Staff Gudang',
                'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-3 hours')),
                'created_at'     => date('Y-m-d H:i:s', strtotime('-3 hours')),
            ],
            [
                'judul'          => 'Laporan Bulanan Inventori - September 2024',
                'jenis'          => 'bulanan',
                'status'         => 'completed',
                'konten'         => 'Laporan inventori bulan September: Total permintaan 45 item, pengiriman berhasil 89%, stok kritis 3 item. Tingkat kepuasan pengguna 94%.',
                'items'          => json_encode([
                    ['barang_id' => 1, 'barang_nama' => 'Laptop Dell XPS 13', 'barang_kode' => 'BRG001', 'jumlah' => 12, 'satuan' => 'unit', 'harga' => 15000000, 'status' => 'processed'],
                    ['barang_id' => 2, 'barang_nama' => 'Mouse Wireless Logitech', 'barang_kode' => 'BRG002', 'jumlah' => 25, 'satuan' => 'unit', 'harga' => 350000, 'status' => 'processed'],
                    ['barang_id' => 3, 'barang_nama' => 'Kertas A4 80gsm', 'barang_kode' => 'BRG003', 'jumlah' => 150, 'satuan' => 'rim', 'harga' => 45000, 'status' => 'processed'],
                    ['barang_id' => 4, 'barang_nama' => 'Tinta Printer Canon', 'barang_kode' => 'BRG004', 'jumlah' => 45, 'satuan' => 'botol', 'harga' => 120000, 'status' => 'processed'],
                    ['barang_id' => 5, 'barang_nama' => 'Headset Gaming HyperX', 'barang_kode' => 'BRG005', 'jumlah' => 8, 'satuan' => 'unit', 'harga' => 1200000, 'status' => 'processed'],
                ]),
                'dibuat_oleh'    => 'Leader Center',
                'tanggal_dibuat' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'periode_dari'   => date('Y-m-d H:i:s', strtotime('-37 days')),
                'periode_sampai' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'created_at'     => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            [
                'judul'          => 'Laporan Pengadaan Laptop Dell XPS 13',
                'jenis'          => 'pengadaan',
                'status'         => 'pending_approval',
                'konten'         => 'Proposal pengadaan 5 unit laptop Dell XPS 13 untuk tim development baru. Total budget dibutuhkan: Rp 75.000.000',
                'items'          => json_encode([
                    [
                        'barang_id' => 1,
                        'barang_nama' => 'Laptop Dell XPS 13',
                        'barang_kode' => 'BRG001',
                        'jumlah' => 5,
                        'satuan' => 'unit',
                        'harga' => 15000000,
                        'status' => 'pending_procurement',
                    ],
                ]),
                'dibuat_oleh'      => 'Manajer Center',
                'tanggal_dibuat'   => date('Y-m-d H:i:s', strtotime('-2 days')),
                'budget_diajukan'  => 75000000,
                'vendor_recommended' => 'PT Teknologi Maju',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'judul'          => 'Laporan Kerusakan dan Maintenance',
                'jenis'          => 'maintenance',
                'status'         => 'in_progress',
                'konten'         => 'Ditemukan 3 unit laptop dengan masalah hardware, 2 unit mouse dengan kerusakan klik kanan, dan 1 printer membutuhkan service rutin.',
                'items'          => json_encode([
                    ['barang_id' => 1, 'barang_nama' => 'Laptop Dell XPS 13', 'barang_kode' => 'BRG001', 'jumlah' => 3, 'satuan' => 'unit', 'status' => 'needs_repair'],
                    ['barang_id' => 2, 'barang_nama' => 'Mouse Wireless Logitech', 'barang_kode' => 'BRG002', 'jumlah' => 2, 'satuan' => 'unit', 'status' => 'needs_repair'],
                ]),
                'dibuat_oleh'        => 'Staff Gudang',
                'tanggal_dibuat'     => date('Y-m-d H:i:s', strtotime('-1 day')),
                'items_terdampak'    => json_encode(['BRG001', 'BRG002']),
                'estimated_completion' => date('Y-m-d H:i:s', strtotime('+3 days')),
                'created_at'         => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'judul'          => 'Laporan Audit Stok Triwulan III',
                'jenis'          => 'audit',
                'status'         => 'completed',
                'konten'         => 'Hasil audit stok triwulan III: Akurasi stok 98.5%, discrepancy minor pada 2 item, rekomendasi penyesuaian sistem tracking.',
                'items'          => json_encode([
                    ['barang_id' => 1, 'barang_nama' => 'Laptop Dell XPS 13', 'barang_kode' => 'BRG001', 'jumlah' => 15, 'satuan' => 'unit', 'status' => 'audited'],
                    ['barang_id' => 2, 'barang_nama' => 'Mouse Wireless Logitech', 'barang_kode' => 'BRG002', 'jumlah' => 45, 'satuan' => 'unit', 'status' => 'audited'],
                    ['barang_id' => 3, 'barang_nama' => 'Kertas A4 80gsm', 'barang_kode' => 'BRG003', 'jumlah' => 200, 'satuan' => 'rim', 'status' => 'audited'],
                    ['barang_id' => 4, 'barang_nama' => 'Tinta Printer Canon', 'barang_kode' => 'BRG004', 'jumlah' => 25, 'satuan' => 'botol', 'status' => 'discrepancy'],
                    ['barang_id' => 5, 'barang_nama' => 'Headset Gaming HyperX', 'barang_kode' => 'BRG005', 'jumlah' => 18, 'satuan' => 'unit', 'status' => 'discrepancy'],
                ]),
                'dibuat_oleh'        => 'Leader Center',
                'tanggal_dibuat'     => date('Y-m-d H:i:s', strtotime('-14 days')),
                'auditor_eksternal'  => 'PT Audit Profesional',
                'accuracy_score'     => 98.5,
                'discrepancies_found' => 2,
                'created_at'         => date('Y-m-d H:i:s', strtotime('-14 days')),
            ],
            [
                'judul'          => 'Laporan Emergency - Permintaan Urgent Laptop',
                'jenis'          => 'emergency',
                'status'         => 'active',
                'priority'       => 'critical',
                'konten'         => 'Permintaan urgent 2 unit laptop sebagai replacement untuk tim development yang laptopnya rusak mendadak. Membutuhkan approval segera.',
                'items'          => json_encode([
                    [
                        'barang_id' => 1,
                        'barang_nama' => 'Laptop Dell XPS 13',
                        'barang_kode' => 'BRG001',
                        'jumlah' => 2,
                        'satuan' => 'unit',
                        'harga' => 15000000,
                        'status' => 'urgent_needed',
                    ],
                ]),
                'dibuat_oleh'      => 'Staff Gudang',
                'tanggal_dibuat'   => date('Y-m-d H:i:s', strtotime('-6 hours')),
                'escalated_to'     => 'Leader Center',
                'sla_deadline'     => date('Y-m-d H:i:s', strtotime('+2 hours')),
                'created_at'       => date('Y-m-d H:i:s', strtotime('-6 hours')),
            ],
        ];

        $this->db->table('laporan')->insertBatch($laporanData);
    }
}