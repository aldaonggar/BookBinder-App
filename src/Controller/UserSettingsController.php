<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Library;
use App\Form\UserSettingsForm;
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
        $user = $this->getUser();

        $libraries = $this->entityManager->getRepository(Library::class)->findAll();

        $form = $this->createForm(UserSettingsForm::class, $user, [
            'libraries' => $libraries,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstname($form->get('firstname')->getData());
            $user->setLastname($form->get('lastname')->getData());

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('usersettings', [
                'libraries' => $libraries,
            ]);
        }

        return $this->render('usersettings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}