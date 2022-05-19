<?php

namespace App\Controller;

use Exception;
use Symfony\{Bundle\FrameworkBundle\Controller\AbstractController,
    Component\HttpFoundation\Response,
    Component\Routing\Annotation\Route
};


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
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('blog/about.html.twig');
    }
}