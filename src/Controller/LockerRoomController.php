<?php

namespace App\Controller;

use App\Entity\LockerRoom;
use App\Repository\LockerRoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

class LockerRoomController extends AbstractController
{

    /**
     * Lists all lockerRoom entities.
     */
    #[Route('/locker/room', name: 'lockerRoom_list', methods: ['GET'])]
    #[Route('/index', name: 'lockerRoom_index', methods: ['GET'])]
    public function listAction(LockerRoomRepository $lockerRoomRepository) : Response
    {
        // Vérifie si l'utilisateur a le rôle ADMIN
        if (!$this->isGranted('ROLE_ADMIN')) {
            // Lève une exception si l'utilisateur n'est pas un administrateur
            throw $this->createAccessDeniedException('Access denied: you are not authorized to view this page.');
        }

        // Charge toutes les LockerRooms uniquement pour les administrateurs
        $lockerRooms = $lockerRoomRepository->findAll();

        return $this->render('locker_room/index.html.twig', [
            'lockerRooms' => $lockerRooms,
    ]);

    }

    /**
     * Show a lockerRoom
     *
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/locker/room/{id}', name: 'lockerRoom_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(LockerRoom $lockerRoom) : Response
    {

        // Vérifier les droits d'accès
        $hasAccess = $this->isGranted('ROLE_ADMIN') ||
            ($this->getUser() === $lockerRoom->getMember());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's locker room!");
        }
            return $this->render('locker_room/show.html.twig',
            [ 'lockerRoom' => $lockerRoom ]
            );
    }
}
