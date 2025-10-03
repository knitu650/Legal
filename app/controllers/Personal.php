<?php
namespace Controllers;

use Core\Controller;
use Models\PersonalDocument;

class Personal extends Controller {
    public function indexAction() {
        $documents = [
            'poa' => 'General Power of Attorney',
            'will' => 'Make Your Will',
            'affidavit' => 'Make Your Own Affidavits Online'
        ];
        $this->render('personal/index', ['documents' => $documents]);
    }

    public function poaAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $personalDoc = new PersonalDocument();
            $result = $personalDoc->createGeneralPOA($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('personal/poa');
    }

    public function willAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $personalDoc = new PersonalDocument();
            $result = $personalDoc->createWill($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('personal/will');
    }

    public function affidavitAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $personalDoc = new PersonalDocument();
            $result = $personalDoc->createAffidavit($_POST);
            echo json_encode($result);
            exit;
        }
        $this->render('personal/affidavit');
    }
}