<?php
namespace Controllers;

use Core\Controller;
use Models\BusinessDocument;

class Business extends Controller {
    public function indexAction() {
        $documents = [
            'nda' => 'Non-Disclosure Agreement',
            'employment' => 'Job offer & Employment Contract',
            'consultancy' => 'Consultancy Agreement'
        ];
        $this->render('business/index', ['documents' => $documents]);
    }

    public function ndaAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $businessDoc = new BusinessDocument();
            $result = $businessDoc->createNDA($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('business/nda');
    }

    public function employmentAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $businessDoc = new BusinessDocument();
            $result = $businessDoc->createEmploymentContract($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('business/employment');
    }

    public function consultancyAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $businessDoc = new BusinessDocument();
            $result = $businessDoc->createConsultancyAgreement($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('business/consultancy');
    }
}