<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Book;
use App\Form\SearchFormType;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ExtraClasses\BookGeneratorForTests;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class BookBinderController extends AbstractController
{
//    /**
//     * @Route("/booklist.html.twig")
//     */
// comment test


    public function renderHomepage(EntityManagerInterface $em):Response
    {
        $repo = $em->getRepository(Book::class);
        $top3Books = $repo->getTop3Books();
        return $this->render('homepage.html.twig', [
            "top3Books"=>$top3Books,
        ]);
    }

}
