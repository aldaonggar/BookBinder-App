<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        return new Response('Well hi there '.$user->getEmail());
    }

    #[Route('/myprofile', name: 'myprofile')]
    public function renderMyProfile(EntityManagerInterface $entityManager):Response
    {
        $user = $this->getUser();
        $favoritedBooks = $user->getFavoriteBooks();


        return $this->render('myprofile.html.twig', [
            'entityManager' => $entityManager,
            'favoritedBooks'=>$favoritedBooks,
        ]);
    }

    #[Route('/usersettings', name: 'usersettings')]
    public function renderUserSettings(EntityManagerInterface $entityManager):Response
    {
        $libraries = $entityManager->getRepository(Library::class)->findAll();

        return $this->render('usersettings.html.twig', [
            'libraries' => $libraries,]);
    }
}