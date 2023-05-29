<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Library;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

class UserSettingsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/editUserSettings", name="editUserSettings", methods={"POST"})
     */
    public function editUserSettings(Request $request): Response
    {
        //$user = $this->security->getUser();
        $userId = $request->request->get('id');

        $user = $this->entityManager->getRepository(User::class)->find($userId);
        $libraries = $this->entityManager->getRepository(Library::class)->findAll();


        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$userId
            );
        }

        $name = $request->request->get('name');
        $surname = $request->request->get('surname');
        $age = $request->request->get('age');
        $birthday = new \DateTimeImmutable($age);
        $sex = $request->request->get('sex');
        $favoriteLibrary = $request->request->get('favoriteLibrary');
        //$favoriteBooks = $request->request->get('favoriteBooks');

        $user->setFirstname($name);
        $user->setLastname($surname);
        $user->setBirthday($birthday);
        $user->setSex($sex);
        $user->setFavoriteLibrary($favoriteLibrary);
//        $user->($favoriteBooks);
        $this->entityManager->flush();

        return $this->redirectToRoute('usersettings', [
            'libraries' => $libraries,]);
    }
}