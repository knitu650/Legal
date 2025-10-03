<?php
namespace Controllers;

use Core\Controller;

class Home extends Controller {
    public function indexAction() {
        $this->render('home/index');
    }
}
?>