<?php

namespace App\Controller;

use App\Entity\Library;
use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LibraryController extends AbstractController
{
    public function renderLibraryList(EntityManagerInterface $entityManager, int $page, Request $request): Response{
        $repository = $entityManager->getRepository(Library::class);
        $library = $repository->get21Libraries($page);
        $numberOfPages = ceil(($repository->getNumberOfLibraries())/21);

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->getData()['searchTerm'];

            return $this->redirectToRoute('searchlibraries', ['searchTerm' => $searchTerm]);
        }

        return $this->render('libraries.html.twig', [
            'libraryArray'=>$library,
            'numberOfPages'=> $numberOfPages,
            'currentPage'=>$page,
            'form'=>$form->createView(),
            'search' => false,
        ]);
    }

    public function renderLibraryListSearch(EntityManagerInterface $entityManager, string $searchTerm, Request $request): Response{
        $repository = $entityManager->getRepository(Library::class);
        $library = $repository->searchLibrariesByNameAndCity($searchTerm);

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->getData()['searchTerm'];

            return $this->redirectToRoute('searchlibraries', ['searchTerm' => $searchTerm]);
        }

        return $this->render('libraries.html.twig', [
            'libraryArray'=>$library,
            'form'=>$form->createView(),
            'search'=>true
        ]);
    }
}