<?php

require_once 'Framework/Controller.php';

class Accueil extends Controller
{
    public function index()
    {
        $this->generateView([]);
    }

}