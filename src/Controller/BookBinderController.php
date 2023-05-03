<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookBinderController extends AbstractController
{
//    /**
//     * @Route("/booklist.html.twig")
//     */
    public function renderBookList(){
        return $this->render('booklist.html.twig');
    }

    public function renderBook(){
        return $this->render('book.html.twig');
    }

    public function renderPerson($id){
        $users = json_decode(file_get_contents($this->getParameter('kernel.project_dir') . '/public/testingdata/user.json'), true);

        $user = null;
        foreach($users as $u) {
            if ($u['id'] == $id) {
                $user = $u;
                break;
            }
        }
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->render('person.html.twig', ['user' => $user]);
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