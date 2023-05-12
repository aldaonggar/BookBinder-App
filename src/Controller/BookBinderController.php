<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ExtraClasses\BookGeneratorForTests;

class BookBinderController extends AbstractController
{
//    /**
//     * @Route("/booklist.html.twig")
//     */
// comment test
    public function renderBookList(){
        $bookGenerator = new BookGeneratorForTests();
        $numberOfPages = 9;
        return $this->render('booklist.html.twig', [
            'bookArray'=>$bookGenerator->getBookArray(),
            'numberOfPages'=> $numberOfPages
        ]);

        /*
         * This chunk is just to see if the book generator works fine
         *
         * $bookGenerator = new BookGeneratorForTests();
        $stringResponse = $bookGenerator->createStringResponse();
        return new Response($stringResponse);*/
    }

    public function renderBook($id){

        return $this->render('book.html.twig');
    }

    public function renderPerson(){
        return $this->render('person.html.twig');
    }

    public function renderUserSettings(){
        return $this->render('usersettings.html.twig');
    }

    public function renderHomepage(){
        return $this->render('homepage.html.twig');
    }

    public function renderLogin(){
        return $this->render('login.html.twig');
    }

    public function renderRegister(){
        return $this->render('register.html.twig');
    }


}