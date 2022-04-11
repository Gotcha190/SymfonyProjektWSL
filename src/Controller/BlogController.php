<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @return Response
     * @throws Exception
     */
    #[Route('/', name: 'homepage')]
    public function blog(): Response
    {
        return $this->render('blog/index.html.twig');
    }

    /**
     * @return Response
     * @throws Exception
     */
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        $number = random_int(0, 100);
        return $this->render('blog/login.html.twig',[
            'number' => $number
        ]);
    }

    /**
     * @return Response
     * @throws Exception
     */
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('blog/about.html.twig');
    }
}