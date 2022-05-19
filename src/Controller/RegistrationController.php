<?php

namespace App\Controller;

use App\{Entity\User, Form\RegistrationFormType};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\{Bundle\FrameworkBundle\Controller\AbstractController,
    Component\HttpFoundation\Request,
    Component\HttpFoundation\Response,
    Component\PasswordHasher\Hasher\UserPasswordHasherInterface,
    Component\Routing\Annotation\Route
};

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
