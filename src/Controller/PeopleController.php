<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PeopleController extends AbstractController
{
    public function renderPeopleList(EntityManagerInterface $entityManager, int $page, Request $request): Response{
        $repository = $entityManager->getRepository(User::class);
        $people = $repository->get21People($page);
        $numberOfPages = ceil(($repository->getNumberOfPeople())/21);

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->getData()['searchTerm'];

            return $this->redirectToRoute('searchpeople', ['searchTerm' => $searchTerm]);
        }

        return $this->render('people.html.twig', [
            'peopleArray'=>$people,
            'numberOfPages'=> $numberOfPages,
            'currentPage'=>$page,
            'form'=>$form->createView(),
            'search' => false,
        ]);
    }

    public function renderPeopleListSearch(EntityManagerInterface $entityManager, string $searchTerm, Request $request): Response{
        $repository = $entityManager->getRepository(User::class);
        $people = $repository->searchPeopleByName($searchTerm);

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->getData()['searchTerm'];

            return $this->redirectToRoute('searchpeople', ['searchTerm' => $searchTerm]);
        }

        return $this->render('people.html.twig', [
            'peopleArray'=>$people,
            'form'=>$form->createView(),
            'search'=>true
        ]);
    }

    public function renderPeople(EntityManagerInterface $entityManager, int $id): Response
    {
        $repository = $entityManager->getRepository(User::class);
        $person = $repository->find($id);
        return $this->render('otheruser.html.twig',[
            'person' => $person,
            'entityManager'=> $entityManager
        ]);
    }
}