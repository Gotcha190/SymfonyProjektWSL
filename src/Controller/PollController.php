<?php

namespace App\Controller;


use App\Entity\Poll;
use App\Entity\PollAnswer;
use App\Form\EditPollType;
use App\Form\PollType;
use App\Repository\PollRepository;
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
    #[Route('/adminPoll', name: 'admin_poll')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $poll = new Poll();
        $chosepoll = new Poll();
        $form = $this->createForm(PollType::class, $poll);
        $chosepoll = $this->createForm(EditPollType::class, $chosepoll);
        //dump($form);
        $form->handleRequest($request);
        $chosepoll->handleRequest($request);
//        $originalAnswers = new ArrayCollection();
//        foreach ($poll->getAnswers() as $answer)
//        {
//            $answer->addPoll($poll);
//            $originalAnswers->add($answer);
//        }
        if ($form->isSubmitted() && $form->isValid())
        {
            $poll = $form->getData();
//            if(isset($data)) dump($data);
//            foreach ($originalAnswers as $answer){
//                if($poll->getAnswers()->contains($answer) === false ){
//                    $manager->remove($answer);
//                }
//            }
            $manager->persist($poll);
            $manager->flush();
            return $this->redirectToRoute('homepage');
        }
        if ($chosepoll->isSubmitted() && $chosepoll->isValid())
        {
            $poll = $chosepoll->get('question')->getData()->getId();
//            dump($poll);
            return $this->redirectToRoute('admin_poll_edit', ['id' => $poll]);
        }
        return $this->renderForm('poll/newPoll.html.twig', [
            'form' => $form,
            'choseform' => $chosepoll,
            'polls' => $this->entityManager->getRepository(Poll::class)->findAll(),
            'answers'=> $this->entityManager->getRepository(PollAnswer::class)->findAll()

        ]);
    }
    /**
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    #[Route('/adminPoll/{id}', name: 'admin_poll_edit')]
    public function edit($id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $chosepoll = new Poll();
        $chosepoll = $this->createForm(EditPollType::class, $chosepoll);
        $chosepoll->handleRequest($request);
        if ($chosepoll->isSubmitted() && $chosepoll->isValid())
        {
            $poll = $chosepoll->get('question')->getData()->getId();
            //dump($poll);
            return $this->redirectToRoute('admin_poll_edit', ['id' => $poll]);
        }
        if (null === $poll = $entityManager->getRepository(Poll::class)->find($id)) {
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
            return $this->redirectToRoute('homepage');
        }
        // ... render some form template
        return $this->renderForm('poll/newPoll.html.twig', [
            'form' => $editForm,
            'choseform' => $chosepoll,
            'polls' => $this->entityManager->getRepository(Poll::class)->findAll(),
            'answers'=> $this->entityManager->getRepository(PollAnswer::class)->findAll()
        ]);
    }

    #[Route('/adminPoll/delete/{id}', name: 'admin_poll_delete')]
    public function delete(Request $request, $id, PollRepository $pollRepository)
    {
        $poll= $pollRepository->find($id);
        $this->entityManager->remove($poll);
        $this->entityManager->flush();
        return $this->redirectToRoute('homepage');
    }
}
