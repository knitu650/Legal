<?php
namespace Models;

use PDO;

class BusinessDocument {
    private $db;

    public function __construct() {
        $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createNDA($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO ndas 
                (party_one, party_two, confidential_info, duration, effective_date, terms)
                VALUES (:party_one, :party_two, :conf_info, :duration, :eff_date, :terms)
            ");

            $stmt->execute([
                ':party_one' => $data['party_one'],
                ':party_two' => $data['party_two'],
                ':conf_info' => $data['confidential_info'],
                ':duration' => $data['duration'],
                ':eff_date' => $data['effective_date'],
                ':terms' => $data['terms']
            ]);

            return ['success' => true, 'message' => 'NDA created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating NDA: ' . $e->getMessage()];
        }
    }

    public function createEmploymentContract($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO employment_contracts 
                (employer_name, employee_name, position, salary, start_date, terms_conditions)
                VALUES (:employer, :employee, :position, :salary, :start_date, :terms)
            ");

            $stmt->execute([
                ':employer' => $data['employer_name'],
                ':employee' => $data['employee_name'],
                ':position' => $data['position'],
                ':salary' => $data['salary'],
                ':start_date' => $data['start_date'],
                ':terms' => $data['terms_conditions']
            ]);

            return ['success' => true, 'message' => 'Employment Contract created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating contract: ' . $e->getMessage()];
        }
    }

    public function createConsultancyAgreement($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO consultancy_agreements 
                (client_name, consultant_name, services, fees, duration, terms)
                VALUES (:client, :consultant, :services, :fees, :duration, :terms)
            ");

            $stmt->execute([
                ':client' => $data['client_name'],
                ':consultant' => $data['consultant_name'],
                ':services' => $data['services'],
                ':fees' => $data['fees'],
                ':duration' => $data['duration'],
                ':terms' => $data['terms']
            ]);

            return ['success' => true, 'message' => 'Consultancy Agreement created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating agreement: ' . $e->getMessage()];
        }
    }
}