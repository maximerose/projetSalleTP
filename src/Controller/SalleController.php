<?php

namespace App\Controller;

use App\Entity\Salle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        if ($numero > 50) {
            throw $this->createNotFoundException('C\'est trop !');
        } else {
            return $this->render(
                'salles/afficher.html.twig',
                ['numero' => $numero]
            );
        }
    }

    public function voir(int $id): Response
    {
        $salle = $this->getDoctrine()->getRepository(Salle::class)->find($id);
        if (!$salle) {
            throw $this->createNotFoundException('Salle[id=' . $id . '] inexistante');
        }
        return $this->render(
            'salles/voir.html.twig',
            [
                'salle' => $salle
            ]
        );
    }

    public function dix(): Response
    {
        return $this->redirectToRoute(
            'salle_tp_afficher',
            ['numero' => 10]
        );
    }

    public function treize(): Response
    {
        $salle = new Salle();
        $salle
            ->setBatiment('D')
            ->setEtage(1)
            ->setNumero(13);
        return $this->render(
            'salles/treize.html.twig',
            [
                'salle' => $salle
            ]
        );
    }

    public function quatorze(): Response
    {
        $salle = new Salle();
        $salle
            ->setBatiment('D')
            ->setEtage(1)
            ->setNumero(14);
        return $this->render(
            'salles/quatorze.html.twig',
            [
                'designation' => $salle->__toString()
            ]
        );
    }

    public function testXml(Request $request): Response
    {
        $remoteAddr = $request->server->get('REMOTE_ADDR');
        $rep = new Response();
        $rep->setContent(
            '<?xm version="1.0" encoding="UTF-8"?><remoteAddr>' .
            $remoteAddr .
            '</remoteAddr>'
        );
        $rep->headers->set('Content-Type', 'text/xml');
        return $rep;
    }

    public function testJson(Request $request): JsonResponse
    {
        $remoteAddr = $request->server->get('REMOTE_ADDR');
        $data = ['remoteAddr' => $remoteAddr];
        return new JsonResponse($data);
    }

    public function ajouter(string $batiment, int $etage, int $numero): Response
    {
        $em = $this->getDoctrine()->getManager();
        $salle = new Salle();
        $salle
            ->setBatiment($batiment)
            ->setEtage($etage)
            ->setNumero($numero);
        $em->persist($salle);
        $em->flush();
        return $this->redirectToRoute(
            'salle_tp_voir',
            [
                'id' => $salle->getId()
            ]
        );
    }

    public function ajouter2(Request $request)
    {
        $salle = new Salle();
        $form = $this->createFormBuilder($salle)
            ->add('batiment', TextType::class)
            ->add('etage', IntegerType::class)
            ->add('numero', IntegerType::class)
            ->add('envoyer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($salle);
            $em->flush();

            return $this->redirectToRoute(
                'salle_tp_voir',
                [
                    'id' => $salle->getId()
                ]
            );
        }

        return $this->render(
            'salles/ajouter2.html.twig',
            [
                'monFormulaire' => $form->createView()
            ]
        );
    }
}