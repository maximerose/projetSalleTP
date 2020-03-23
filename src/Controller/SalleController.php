<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class SalleController
{
    public function accueil ()
    {
        return new Response("<html><body><h1>Salles :</h1> <p>Voici quelques informations concernant les salles<br \>blablabla blablabla blablabla blablabla blablabla blablabla blablabla blablabla ....</p></body></html>");
    }
}