<?php

require_once 'Framework/Controleur.php';

class ControleurAccueil extends Controleur
{
    public function index()
    {
        $this->genererVue([]);
    }

}