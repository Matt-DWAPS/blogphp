<?php

require_once 'Framework/Controleur.php';

class Accueil extends Controleur
{
    public function index()
    {
        $this->genererVue([]);
    }

}