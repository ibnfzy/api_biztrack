<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use DateTime;

class ReportsController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * GET /laporan
     * Retrieve all reports.
     */
    public function index()
    {
        $reports = $this->fetchReports();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * GET /laporan/penjualan
     * Retrieve sales reports with optional date filters.
     */
    public function sales()
    {
        $reports = $this->fetchReports('penjualan');

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * GET /laporan/permintaan
     * Retrieve request reports with optional date filters.
     */
    public function requests()
    {
        $reports = $this->fetchReports('permintaan');

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * GET /laporan/pengiriman
     * Retrieve delivery reports with optional date filters.
     */
    public function deliveries()
    {
        $reports = $this->fetchReports('pengiriman');

        return $this->response->setJSON([
            'success' => true,
            'data'    => $reports,
        ]);
    }

    /**
     * POST /laporan
     * Create a new report entry.
     */
    public function create()
    {
        $data = $this->request->getJSON(true) ?? [];
        $now  = (new DateTime())->format('Y-m-d H:i:s');

        $insert = [
            'judul'         => $data['judul'] ?? null,
            'jenis'         => $data['jenis'] ?? null,
            'pembuat'       => $data['pembuat'] ?? null,
            'tanggalDibuat' => $data['tanggalDibuat'] ?? $now,
            'status'        => $data['status'] ?? 'draft',
            'data'          => isset($data['data']) ? json_encode($data['data']) : json_encode(new \stdClass()),
            'createdAt'     => $now,
        ];

        $this->db->table('laporan')->insert($insert);
        $reportId = $this->db->insertID();

        $report = $this->db->table('laporan')
            ->where('id', $reportId)
            ->get()
            ->getRowArray();

        if ($report) {
            $report['data'] = $this->decodeData($report['data'] ?? null);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $report,
        ]);
    }

    /**
     * DELETE /laporan/{id}
     * Remove a report entry.
     */
    public function delete($id)
    {
        $this->db->table('laporan')->where('id', $id)->delete();

        return $this->response->setJSON([
            'success' => true,
            'data'    => null,
        ]);
    }

    /**
     * Fetch reports optionally filtered by type and date range.
     */
    protected function fetchReports(?string $type = null): array
    {
        $builder = $this->db->table('laporan');

        if ($type !== null) {
            $builder->where('jenis', $type);
        }

        $startDate = $this->request->getGet('startDate');
        $endDate   = $this->request->getGet('endDate');

        if (! empty($startDate)) {
            $builder->where('tanggalDibuat >=', $startDate);
        }

        if (! empty($endDate)) {
            $builder->where('tanggalDibuat <=', $endDate);
        }

        $reports = $builder->get()->getResultArray();

        foreach ($reports as &$report) {
            $report['data'] = $this->decodeData($report['data'] ?? null);
        }

        return $reports;
    }

    /**
     * Safely decode report data JSON.
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
