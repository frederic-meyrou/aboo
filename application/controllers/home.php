<?php

class Home extends CI_Controller
{
    public function accueil($pseudo = '')
    {
        echo 'Hello World!' . $pseudo;
    }
}