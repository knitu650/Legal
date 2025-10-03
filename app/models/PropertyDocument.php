<?php
namespace Models;

use PDO;

class PropertyDocument {
    private $db;

    public function __construct() {
        $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createRentalAgreement($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO rental_agreements 
                (landlord_name, tenant_name, property_address, rent_amount, duration, start_date, terms)
                VALUES (:landlord, :tenant, :address, :rent, :duration, :start_date, :terms)
            ");

            $stmt->execute([
                ':landlord' => $data['landlord_name'],
                ':tenant' => $data['tenant_name'],
                ':address' => $data['property_address'],
                ':rent' => $data['rent_amount'],
                ':duration' => $data['duration'],
                ':start_date' => $data['start_date'],
                ':terms' => $data['terms']
            ]);

            return ['success' => true, 'message' => 'Rental Agreement created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating agreement: ' . $e->getMessage()];
        }
    }

    public function createCommercialAgreement($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO commercial_agreements 
                (owner_name, business_name, property_address, rent_amount, lease_term, start_date, terms)
                VALUES (:owner, :business, :address, :rent, :term, :start_date, :terms)
            ");

            $stmt->execute([
                ':owner' => $data['owner_name'],
                ':business' => $data['business_name'],
                ':address' => $data['property_address'],
                ':rent' => $data['rent_amount'],
                ':term' => $data['lease_term'],
                ':start_date' => $data['start_date'],
                ':terms' => $data['terms']
            ]);

            return ['success' => true, 'message' => 'Commercial Agreement created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating agreement: ' . $e->getMessage()];
        }
    }

    public function createPropertyPOA($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO property_poa 
                (grantor_name, attorney_name, property_details, powers_granted, start_date, end_date)
                VALUES (:grantor, :attorney, :property, :powers, :start_date, :end_date)
            ");

            $stmt->execute([
                ':grantor' => $data['grantor_name'],
                ':attorney' => $data['attorney_name'],
                ':property' => $data['property_details'],
                ':powers' => $data['powers_granted'],
                ':start_date' => $data['start_date'],
                ':end_date' => $data['end_date']
            ]);

            return ['success' => true, 'message' => 'Property POA created successfully'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => 'Error creating POA: ' . $e->getMessage()];
        }
    }
}