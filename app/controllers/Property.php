<?php
namespace Controllers;

use Core\Controller;
use Models\PropertyDocument;

class Property extends Controller {
    public function indexAction() {
        $documents = [
            'rental' => 'Rental Agreement',
            'commercial' => 'Commercial Rental Agreement',
            'poa' => 'General POA for Property'
        ];
        $this->render('property/index', ['documents' => $documents]);
    }

    public function rentalAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propertyDoc = new PropertyDocument();
            $result = $propertyDoc->createRentalAgreement($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('property/rental');
    }

    public function commercialAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propertyDoc = new PropertyDocument();
            $result = $propertyDoc->createCommercialAgreement($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('property/commercial');
    }

    public function poaAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propertyDoc = new PropertyDocument();
            $result = $propertyDoc->createPropertyPOA($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('property/poa');
    }
}