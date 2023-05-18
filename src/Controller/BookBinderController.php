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
    #[Route('/booklist', name: 'booklist')]
    public function renderBookList()
    {
        return $this->render('booklist.html.twig');
    }

    #[Route('/book', name: 'book')]
    public function renderBook()
    {
        return $this->render('book.html.twig');
    }

    #[Route('/person', name: 'person')]
    public function renderPerson()
    {
        return $this->render('person.html.twig');
    }

    #[Route('/myprofile', name: 'myprofile')]
    public function renderMyProfile()
    {
        return $this->render('myprofile.html.twig');
    }

    #[Route('/usersettings', name: 'usersettings')]
    public function renderUserSettings()
    {
        return $this->render('usersettings.html.twig');
    }

    #[Route('/home', name: 'home')]
    public function renderHomepage()
    {
        return $this->render('homepage.html.twig');
    }

}
