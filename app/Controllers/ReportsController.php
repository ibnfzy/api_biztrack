<?php

namespace App\Controllers;

use Config\Database;
use DateTime;

class ReportsController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * GET /laporan
     * Retrieve all reports (optionally filtered by cabang_id).
     */
    public function index()
    {
        $reports = $this->fetchReports();

        return respondSuccess($this->response, $reports);
    }

    /**
     * GET /laporan/penjualan
     */
    public function sales()
    {
        $reports = $this->fetchReports('penjualan');

        return respondSuccess($this->response, $reports);
    }

    /**
     * GET /laporan/permintaan
     */
    public function requests()
    {
        $reports = $this->fetchReports('permintaan');

        return respondSuccess($this->response, $reports);
    }

    /**
     * GET /laporan/pengiriman
     */
    public function deliveries()
    {
        $reports = $this->fetchReports('pengiriman');

        return respondSuccess($this->response, $reports);
    }

    /**
     * POST /laporan
     */
    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $cabang_id = $data['cabang_id'] ?? null;

        if (empty($cabang_id)) {
            return respondBadRequest($this->response, 'cabang_id is required.');
        }

        $insert = [
            'cabang_id'         => $cabang_id,
            'judul'             => $data['judul'] ?? $data['title'] ?? '',
            'jenis'             => $data['jenis'] ?? $data['type'] ?? '',
            'status'            => $data['status'] ?? 'draft',
            'konten'            => $data['konten'] ?? '',
            'items'             => isset($data['items']) ? json_encode($data['items']) : json_encode([]),
            'dibuat_oleh'       => $data['dibuat_oleh'] ?? '',
            'tanggal_dibuat'    => $data['tanggal_dibuat'] ?? $now,
            'created_at'        => $data['created_at'] ?? $now,
            'updated_at'        => $now,

            // Optional fields
            'periode_dari'         => $data['periode_dari'] ?? null,
            'periode_sampai'       => $data['periode_sampai'] ?? null,
            'budget_diajukan'      => $data['budget_diajukan'] ?? 0,
            'vendor_recommended'   => $data['vendor_recommended'] ?? '',
            'items_terdampak'      => isset($data['items_terdampak']) ? json_encode($data['items_terdampak']) : json_encode([]),
            'estimated_completion' => $data['estimated_completion'] ?? null,
            'auditor_eksternal'    => $data['auditor_eksternal'] ?? '',
            'accuracy_score'       => $data['accuracy_score'] ?? 0.00,
            'discrepancies_found'  => $data['discrepancies_found'] ?? 0,
            'priority'             => $data['priority'] ?? '',
            'escalated_to'         => $data['escalated_to'] ?? '',
            'sla_deadline'         => $data['sla_deadline'] ?? null,
        ];

        $this->db->table('laporan')->insert($insert);
        $reportId = $this->db->insertID();

        // Ambil kembali laporan beserta info cabangnya
        $report = $this->db->table('laporan l')
            ->select('l.*, c.nama_cabang, c.alamat AS alamat_cabang, c.telepon AS telepon_cabang')
            ->join('cabang c', 'c.id = l.cabang_id', 'left')
            ->where('l.id', $reportId)
            ->get()
            ->getRowArray();

        if ($report) {
            $report['items'] = $this->decodeData($report['items'] ?? null);
        }

        return respondSuccess($this->response, $report, 'Laporan berhasil dibuat.', 201);
    }

    /**
     * DELETE /laporan/{id}
     */
    public function delete($id)
    {
        $this->db->table('laporan')->where('id', $id)->delete();

        return respondSuccess($this->response, null, 'Laporan berhasil dihapus.');
    }

     /**
     * Ambil semua laporan + info cabang
     */
    protected function fetchReports(?string $type = null): array
    {
        $builder = $this->db->table('laporan l')
            ->select('
                l.*, 
                c.nama_cabang, 
                c.alamat AS alamat_cabang, 
                c.telepon AS telepon_cabang
            ')
            ->join('cabang c', 'c.id = l.cabang_id', 'left');

        if ($type !== null) {
            $builder->where('l.jenis', $type);
        }

        // Filter cabang
        $cabang_id = $this->request->getGet('cabang_id');
        if (!empty($cabang_id)) {
            $builder->where('l.cabang_id', $cabang_id);
        }

        // Filter tanggal
        $startDate = $this->request->getGet('startDate');
        $endDate   = $this->request->getGet('endDate');

        if (!empty($startDate)) {
            $builder->where('l.tanggal_dibuat >=', $startDate);
        }

        if (!empty($endDate)) {
            $builder->where('l.tanggal_dibuat <=', $endDate);
        }

        $builder->orderBy('l.tanggal_dibuat', 'DESC');
        $reports = $builder->get()->getResultArray();

        foreach ($reports as &$report) {
            $report['items'] = $this->decodeData($report['items'] ?? null);
        }

        return $reports;
    }

    /**
     * Decode JSON safely.
     */
    protected function decodeData(?string $payload)
    {
        if ($payload === null || $payload === '') {
            return new \stdClass();
        }

        $decoded = json_decode($payload, true);

        return $decoded === null ? new \stdClass() : $decoded;
    }
}
