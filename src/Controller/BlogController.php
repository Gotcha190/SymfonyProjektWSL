<?php

namespace App\Controller;

use App\Entity\Poll;
use App\Entity\PollAnswer;
use App\Form\PollFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Exception;
use Symfony\{Bundle\FrameworkBundle\Controller\AbstractController,
    Component\HttpFoundation\Request,
    Component\HttpFoundation\Response,
    Component\Routing\Annotation\Route};


class BlogController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Response
     * @throws Exception
     */
    #[Route('/', name: 'homepage')]
    public function blog(): Response
    {
        //dump($this->PollRepository->findAll());
        return $this->render('blog/index.html.twig',[
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
//    #[Route('/createPoll', name: 'createPoll')]
//    public function createPoll(Request $request, ManagerRegistry $doctrine) : Response
//    {
//        $manager = $doctrine->getManager();
//        $poll = new Poll();
//        $originalAns = new ArrayCollection();
//        $pollAnswers = new PollAnswer();
//        //dump($this->PollAnswerRepository->findAll());
//        foreach ($poll->getPollAnswers() as $answer){
//            $originalAns->add($answer);
//        }
//        $form = $this->createForm(PollFormType::class, $poll);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $poll = $form->getData();
//            foreach ($originalAns as $answer){
//                if($poll->getPollAnswers()->contains($answer) === false ){
//                    $manager->remove($answer);
//                }
//            }
//            $manager->persist($poll);
//            $manager->flush();
//            return $this->redirectToRoute('homepage');
//        }
//        return $this->renderForm('/blog/newPoll.html.twig',[
//            'pollForm' => $form,
//        ]);
//    }
}