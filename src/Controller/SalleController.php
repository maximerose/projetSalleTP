<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class SalleController
{
    public function accueil()
    {
        return new Response("Ici l'accueil !");
    }
}