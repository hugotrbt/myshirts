<?php

namespace App\Controller;

use App\Entity\LockerRoom;
use App\Entity\Stadium;
use App\Entity\Shirt;
use App\Form\StadiumType;
use App\Repository\StadiumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stadium')]
final class StadiumController extends AbstractController
{
    #[Route(name: 'app_stadium_index', methods: ['GET'])]
    public function index(StadiumRepository $stadiumRepository): Response
    {

        // Récupère l'utilisateur connecté
        $member = $this->getUser();

         // Si l'utilisateur est un administrateur, on peut charger toutes les galeries
        if ($this->isGranted('ROLE_ADMIN')) {
            $stadia = $stadiumRepository->findAll();
            
        } elseif ($member) {
            
            // Si l'utilisateur est connecté, on charge les stadiums publics et ses privés
            $publicStadiums = $stadiumRepository->findBy(['published' => true]); // Stadiums publics
            $privateStadiums = $stadiumRepository->findBy([
                'published' => false,
                'creator' => $member // Stadiums privés appartenant au membre connecté
            ]);
            // Fusionne les publics et les privés de l'utilisateur
            $stadia = array_merge($publicStadiums, $privateStadiums);
        } else {
            // Si l'utilisateur n'est pas connecté, il voit uniquement les stadiums publics
            $stadia = $stadiumRepository->findBy(['published' => true]);
        }
    
        return $this->render('stadium/index.html.twig', [
            'stadia' => $stadia,
        ]);
    }

    #[Route('/stadium/new/{id}', name: 'app_stadium_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, LockerRoom $lockerRoom): Response
    {
        $stadium = new Stadium();

        // On passe par Member pour récupérer le lien entre LockerRoom et Stadium
        $member = $lockerRoom->getMember();

        //On associe donc le stadium dirctement au membre
        $stadium->setCreator($member);

        $form = $this->createForm(StadiumType::class, $stadium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stadium);
            $entityManager->flush();

            return $this->redirectToRoute('lockerRoom_show', ['id' => $lockerRoom->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stadium/new.html.twig', [
            'stadium' => $stadium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stadium_show', methods: ['GET'])]
    public function show(Stadium $stadium): Response
    {
        $hasAccess = false;
        if($this->isGranted('ROLE_ADMIN') || $stadium->isPublished()) {
                $hasAccess = true;
        }
        else {
                $member = $this->getUser();
                if ( $member &&  ($member == $stadium->getCreator()) ) {
                    $hasAccess = true;
                }
        }
        if(! $hasAccess) {
                throw $this->createAccessDeniedException("You cannot access the requested resource!");
        }
        return $this->render('stadium/show.html.twig', [
            'stadium' => $stadium,
        ]);
    }

    /**
     * Show a shirt in a stadium
     *
     * @param Stadium $stadium (the stadium which displays the shirt)
     * @param Shirt $shirt (the shirt to display)
     */
    #[Route('/{stadium_id}/shirt/{shirt_id}', name: 'app_stadium_shirtshow', methods: ['GET'],
        requirements: ['stadium_id' => '\d+',
                        'shirt_id' => '\d+'
                        ])]
    public function shirtShow(#[MapEntity(id: 'stadium_id')]Stadium $stadium,#[MapEntity(id: 'shirt_id')]Shirt $shirt) : Response
    {
            if(! $stadium->getShirts()->contains($shirt)) {
                throw $this->createNotFoundException("Couldn't find such a [objet] in this [galerie]!");
            }

            $hasAccess = false;
            if($this->isGranted('ROLE_ADMIN') || $stadium->isPublished()) {
                    $hasAccess = true;
            }
            else {
                    $member = $this->getUser();
            if ( $member &&  ($member == $stadium->getCreator()) ) {
                $hasAccess = true;
            }
            }
            if(! $hasAccess) {
                    throw $this->createAccessDeniedException("You cannot access the requested ressource!");
            }
            return $this->render('stadium/shirtshow.html.twig', [
                'shirt' => $shirt,
                'stadium' => $stadium,
            ]);
    }

    #[Route('/{id}/edit', name: 'app_stadium_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stadium $stadium, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StadiumType::class, $stadium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('lockerRoom_show', ['id' => $stadium->getCreator()->getLockerRoom()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stadium/edit.html.twig', [
            'stadium' => $stadium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stadium_delete', methods: ['POST'])]
    public function delete(Request $request, Stadium $stadium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stadium->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stadium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lockerRoom_show', ['id' => $stadium->getCreator()->getLockerRoom()->getId()], Response::HTTP_SEE_OTHER);
    }
}
