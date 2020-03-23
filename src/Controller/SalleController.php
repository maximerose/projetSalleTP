<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SalleController extends AbstractController
{
    public function accueil(): Response
    {
        return $this->render(
            'salles/accueil.html.twig',
            ['numero' => rand(1, 84)]
        );
    }

    public function afficher(int $numero): Response
    {
        return $this->render(
            'salles/afficher.html.twig',
            ['numero' => $numero]
        );
    }
}