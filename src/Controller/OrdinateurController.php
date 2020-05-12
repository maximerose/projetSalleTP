<?php

namespace App\Controller;

use App\Entity\Ordinateur;
use App\Form\OrdinateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrdinateurController extends AbstractController
{
    /**
     * @Route("/ordinateur", name="ordinateur")
     */
    public function index()
    {
        return $this->render('ordinateur/index.html.twig', [
            'controller_name' => 'OrdinateurController',
        ]);
    }
    /**
     * @Route("/ajout_ordinateur", name="ajout_ordinateur")
     */
    public function ajouter(Request $request) {
        $ordinateur = new Ordinateur;
        $form = $this->createForm(OrdinateurType::class, $ordinateur);
        $form->add('submit', SubmitType::class,
            array('label' => 'Ajouter'));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ordinateur);
            $entityManager->flush();
            return $this->redirectToRoute('ordinateur');
        }
        return $this->render('ordinateur/ajouter.html.twig',
            array('monFormulaire' => $form->createView()));
    }
}
