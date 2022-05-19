<?php

namespace App\Controller;

use Symfony\{Component\HttpFoundation\Response,
    Bundle\FrameworkBundle\Controller\AbstractController,
    Component\Routing\Annotation\Route
};

class LuckyController extends AbstractController
{

    #[Route('/lucky', name: 'lucky')]
    public function number(): Response
    {
        $number = random_int(0, 100);

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}