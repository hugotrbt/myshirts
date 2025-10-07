<?php

namespace App\Controller;

use App\Entity\LockerRoom;
use App\Entity\Shirt;
use App\Form\ShirtType;
use App\Repository\ShirtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/shirt')]
final class ShirtController extends AbstractController
{
    #[Route(name: 'app_shirt_index', methods: ['GET'])]
    public function index(ShirtRepository $shirtRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $shirts = $shirtRepository->findAll();
        }
        else {
                $member = $this->getUser();
                $shirts = $shirtRepository->findMemberShirts($member);
        }
        return $this->render('shirt/index.html.twig', [
            'shirts' => $shirts,
        ]);
    }

    #[Route('/shirt/new/{id}', name: 'app_shirt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, LockerRoom $lockerRoom): Response
    {
        $shirt = new Shirt();
        $shirt->setLockerRoom($lockerRoom);
        $form = $this->createForm(ShirtType::class, $shirt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Change le contenu selon l'image
            $imagefile = $shirt->getImageFile();
            if($imagefile) {
                    $mimetype = $imagefile->getMimeType();
                    $shirt->setContentType($mimetype);
            }
            $entityManager->persist($shirt);
            $entityManager->flush();

            return $this->redirectToRoute('lockerRoom_show', ['id' => $lockerRoom->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shirt/new.html.twig', [
            'shirt' => $shirt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shirt_show', methods: ['GET'])]
    public function show(Shirt $shirt): Response
    {
        
        return $this->render('shirt/show.html.twig', [
            'shirt' => $shirt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shirt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shirt $shirt, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShirtType::class, $shirt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('lockerRoom_show', ['id' => $shirt->getLockerRoom()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shirt/edit.html.twig', [
            'shirt' => $shirt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shirt_delete', methods: ['POST'])]
    public function delete(Request $request, Shirt $shirt, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shirt->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($shirt);
            $entityManager->flush();
        }

        return $this->redirectToRoute('lockerRoom_show', ['id' => $shirt->getLockerRoom()->getId()], Response::HTTP_SEE_OTHER);
    }
}
