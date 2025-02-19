<?php

namespace App\Controllers;

use App\Core\Controller;

class Home extends Controller {
    /**
     * Display the home page
     *
     * @return void
     */
    public function indexAction() {
        $this->render('home/index');
    }
} 