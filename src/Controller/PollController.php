<?php

namespace App\Controller;


use App\Entity\Poll;
use App\Entity\PollAnswer;
use App\Form\PollType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PollController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/poll', name: 'app_poll')]
    public function index(): Response
    {
        return $this->render('poll/index.html.twig', [
            'controller_name' => 'PollController',
        ]);
    }
    #[Route('/newPoll', name: 'new_poll')]
    public function newPoll(Request $request, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $poll = new Poll();

//        $poll->setQuestion('hallo?');
//        $answer1 = new PollAnswer();
//        $answer1->setText('answer1');
//        $poll->addAnswer($answer1);
//        $answer2 = new PollAnswer();
//        $answer2->setText('answer2');
//        $poll->addAnswer($answer2);
        //dump($poll);
        //dump($answer1);

        $form = $this->createForm(PollType::class, $poll);
        //dump($form);
        $form->handleRequest($request);
        $originalAnswers = new ArrayCollection();
        foreach ($poll->getAnswers() as $answer)
        {
            $answer->addPoll($poll);
            $originalAnswers->add($answer);
        }
        if ($form->isSubmitted() && $form->isValid())
        {
            $poll = $form->getData();
            foreach ($originalAnswers as $answer){
                if($poll->getAnswers()->contains($answer) === false ){
                    $manager->remove($answer);
                }
            }
            $manager->persist($poll);
            $manager->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('poll/newPoll.html.twig', [
            'form' => $form,
        ]);
    }
    /**
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (null === $task = $entityManager->getRepository(PollAnswer::class)->find($id)) {
            throw new \Exception ('No task found for id ' . $id);
        }

        $originalAnswers = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($poll->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }

        $editForm = $this->createForm(PollType::class, $poll);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // remove the relationship between the tag and the Task
            foreach ($originalAnswers as $answer) {
                if (false === $poll->getAnswers()->contains($answer)) {
                    // remove the Task from the Tag
                    $answer->getPoll()->removeElement($poll);
                    $entityManager->persist($answer);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }
            }

            $entityManager->persist($poll);
            $entityManager->flush();

            // redirect back to some edit page
            return $this->redirectToRoute('homepage', ['id' => $id]);
        }
        // ... render some form template
    }
}
