<?php

namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Service\ImageTexteGenerateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SalleController extends AbstractController
{
    public function accueil(Session $session): Response
    {
        if ($session->has('nbreFois')) {
            $session->set('nbreFois', $session->get('nbreFois') + 1);
        } else {
            $session->set('nbreFois', 1);
        }
        return $this->render(
            'salles/accueil.html.twig',
            ['nbreFois' => $session->get('nbreFois')]
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

    public function voir(Salle $salle): Response
    {
        return $this->render('salles/voir.html.twig', ['salle' => $salle]);
    }

    public function voirautrement(ImageTexteGenerateur $texte2image, $id)
    {
        $salle = $this->getDoctrine()->getRepository(Salle::class)->find($id);

        if (!$salle)
            throw $this->createNotFoundException('Salle[id=' . $id . '] inexistante');

        $sourceDataUri = $texte2image->texte2Image($salle->__toString());

        return $this->render(
            'salles/voirautrement.html.twig',
            ['dataURI' => $sourceDataUri]
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

    public function ajouter(string $batiment, int $etage, int $numero, ValidatorInterface $validator): Response
    {
        $em = $this->getDoctrine()->getManager();
        $salle = new Salle();
        $salle
            ->setBatiment($batiment)
            ->setEtage($etage)
            ->setNumero($numero);

        $listeErreurs = $validator->validate($salle);

        if ($listeErreurs->count() > 0) {
            throw $this->createNotFoundException('Mauvaise saisie de données : ' . $listeErreurs->__toString());
        }

        $em->persist($salle);
        $em->flush();
        return $this->redirectToRoute(
            'salle_tp_voir',
            [
                'id' => $salle->getId()
            ]
        );
    }

    public function ajouter2(Request $request): Response
    {
        $salle = new Salle();

        $form = $this->createForm(
            SalleType::class,
            $salle,
            ['action' => $this->generateUrl('salle_tp_ajouter2')]
        );

        $form->add('submit', SubmitType::class, ['label' => 'Ajouter']);

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

    public function navigation()
    {
        $salles = $this->getDoctrine()
            ->getRepository(Salle::class)->findAll();
        return $this->render('salles/navigation.html.twig',
            array('salles' => $salles));
    }

    public function modifier(int $id): Response
    {
        $salle = $this->getDoctrine()->getRepository(Salle::class)->find($id);

        if (!$salle)
            throw $this->createNotFoundException('Salle[id=' . $id . '] inexistante');

        $form = $this->createForm(
            SalleType::class,
            $salle,
            ['action' => $this->generateUrl(
                'salle_tp_modifier_suite',
                ['id' => $salle->getId()])
            ]);

        $form->add('submit', SubmitType::class, ['label' => 'Modifier']);

        return $this->render('salles/modifier.html.twig', ['monFormulaire' => $form->createView()]);
    }

    public function modifierSuite(Request $request, $id)
    {
        $salle = $this->getDoctrine()->getRepository(Salle::class)->find($id);

        if (!$salle)
            throw $this->createNotFoundException('Salle[id=' . $id . '] inexistante');

        $form = $this->createForm(
            SalleType::class, $salle,
            ['action' => $this->generateUrl(
                'salle_tp_modifier_suite',
                ['id' => $salle->getId()])
            ]);
        $form->add('submit', SubmitType::class, ['label' => 'Modifier']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($salle);
            $entityManager->flush();
            $url = $this->generateUrl('salle_tp_voir', ['id' => $salle->getId()]);

            return $this->redirect($url);
        }

        return $this->render(
            'salles/modifier.html.twig',
            ['monFormulaire' => $form->createView()]
        );
    }
}