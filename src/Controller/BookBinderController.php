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
    public function renderBookList($page){
        $bookGenerator = new BookGeneratorForTests($page);
        $numberOfPages = 3;
        return $this->render('booklist.html.twig', [
            'bookArray'=>$bookGenerator->getBookArray(),
            'numberOfPages'=> $numberOfPages,
            'currentPage'=>$page
        ]);

        /*
         * This chunk is just to see if the book generator works fine
         *
         * $bookGenerator = new BookGeneratorForTests();
        $stringResponse = $bookGenerator->createStringResponse();
        return new Response($stringResponse);*/
    }

    public function renderBook($id){
        $bookGenerator = new BookGeneratorForTests($id/20);
        $bookArray = $bookGenerator->getBookArray();
        $bookForPage = null;
        foreach ($bookArray as $book){
            if ($book->getId() == $id){
                $bookForPage = $book;
            }
        }
        if ($bookForPage != null){
            return $this->render('book.html.twig',[
                'book'=>$bookForPage
            ]);
        }
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