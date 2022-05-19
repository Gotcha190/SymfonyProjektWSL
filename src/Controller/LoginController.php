<?php

namespace App\Controller;

use Symfony\{Bundle\FrameworkBundle\Controller\AbstractController,
    Component\HttpFoundation\Response,
    Component\Routing\Annotation\Route,
    Component\Security\Http\Authentication\AuthenticationUtils
};

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
