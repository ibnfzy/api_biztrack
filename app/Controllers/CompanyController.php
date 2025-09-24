<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use DateTime;

class CompanyController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * GET /company
     * Retrieve company settings record.
     */
    public function index()
    {
        $company = $this->db->table('company_settings')->get()->getRowArray();

        $company = $company ? $this->formatCompany($company) : $this->defaultCompany();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $company,
        ]);
    }

    /**
     * PUT /company
     * Update existing company settings or create a new one when missing.
     */
    public function update()
    {
        $payload = $this->request->getJSON(true) ?? [];
        $fields  = ['name', 'address', 'phone', 'email', 'logo', 'settings'];
        $update  = [];

        foreach ($fields as $field) {
            if (array_key_exists($field, $payload)) {
                $update[$field] = $payload[$field];
            }
        }

        if (array_key_exists('settings', $update)) {
            $settings = $update['settings'];
            if (is_array($settings) || is_object($settings)) {
                $update['settings'] = json_encode($settings);
            }
        }

        if (empty($update)) {
            $company = $this->db->table('company_settings')->get()->getRowArray();

            return $this->response->setJSON([
                'success' => true,
                'data'    => $company ? $this->formatCompany($company) : $this->defaultCompany(),
            ]);
        }

        $now     = (new DateTime())->format('Y-m-d H:i:s');
        $table   = $this->db->table('company_settings');
        $current = $table->get()->getRowArray();

        $update['updatedAt'] = $now;

        if ($current) {
            $table->where('id', $current['id'])->update($update);
            $id = $current['id'];
        } else {
            $update['createdAt'] = $now;
            $table->insert($update);
            $id = $this->db->insertID();
        }

        $company = $table->where('id', $id)->get()->getRowArray();

        return $this->response->setJSON([
            'success' => true,
            'data'    => $company ? $this->formatCompany($company) : $this->defaultCompany(),
        ]);
    }

    private function formatCompany(array $company): array
    {
        if (isset($company['settings'])) {
            $settings = $company['settings'];
            if (is_string($settings)) {
                $decoded = json_decode($settings, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $settings = $decoded;
                }
            }

            if ($settings === null) {
                $settings = [];
            }

            if ($settings instanceof \stdClass) {
                $settings = (array) $settings;
            }

            if (! is_array($settings)) {
                $settings = [];
            }

            $company['settings'] = empty($settings) ? new \stdClass() : $settings;
        } else {
            $company['settings'] = new \stdClass();
        }

        return $this->filterCompanyFields($company);
    }

    private function defaultCompany(): array
    {
        return [
            'name'     => null,
            'address'  => null,
            'phone'    => null,
            'email'    => null,
            'logo'     => null,
            'settings' => new \stdClass(),
        ];
    }

    private function filterCompanyFields(array $company): array
    {
        $allowed = ['name', 'address', 'phone', 'email', 'logo', 'settings'];

        $filtered = [];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $company)) {
                $filtered[$field] = $company[$field];
            } else {
                $filtered[$field] = $field === 'settings' ? new \stdClass() : null;
            }
        }

        return $filtered;
    }
}
