<?php
namespace Models;

use PDO;

class PersonalDocument {
    private $db;

    public function __construct() {
        $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createGeneralPOA($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO general_poa 
                (grantor_name, attorney_name, powers_granted, start_date, end_date, witnesses)
                VALUES (:grantor, :attorney, :powers, :start_date, :end_date, :witnesses)
            ");

            $stmt->execute([
                ':grantor' => $data['grantor_name'],
                ':attorney' => $data['attorney_name'],
                ':powers' => $data['powers_granted'],
                ':start_date' => $data['start_date'],
                ':end_date' => $data['end_date'],
                ':witnesses' => json_encode($data['witnesses'])
            ]);

            return ['success' => true, 'message' => 'General POA created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating POA: ' . $e->getMessage()];
        }
    }

    public function createWill($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO wills 
                (testator_name, beneficiaries, assets, executors, witnesses, date_created)
                VALUES (:testator, :beneficiaries, :assets, :executors, :witnesses, NOW())
            ");

            $stmt->execute([
                ':testator' => $data['testator_name'],
                ':beneficiaries' => json_encode($data['beneficiaries']),
                ':assets' => json_encode($data['assets']),
                ':executors' => json_encode($data['executors']),
                ':witnesses' => json_encode($data['witnesses'])
            ]);

            return ['success' => true, 'message' => 'Will created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating Will: ' . $e->getMessage()];
        }
    }

    public function createAffidavit($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO affidavits 
                (deponent_name, affidavit_type, content, place, date_created)
                VALUES (:deponent, :type, :content, :place, NOW())
            ");

            $stmt->execute([
                ':deponent' => $data['deponent_name'],
                ':type' => $data['affidavit_type'],
                ':content' => $data['content'],
                ':place' => $data['place']
            ]);

            return ['success' => true, 'message' => 'Affidavit created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating Affidavit: ' . $e->getMessage()];
        }
    }
}