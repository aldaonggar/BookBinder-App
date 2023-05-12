<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class BookBinderController extends AbstractController
{
//    /**
//     * @Route("/booklist.html.twig")
//     */
// comment test
    public function renderBookList(){
        return $this->render('booklist.html.twig');
    }

    public function renderBook(){
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

    public function renderMyprofile(){
        return $this->render('myprofile.html.twig');
    }

    public function renderLibraries(){
        return $this->render('libraries.html.twig');
    }

}