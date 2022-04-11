<?php

namespace App\Controller;

use App\Entity\BlogArticle;
use App\Entity\BlogCategory;
use App\Repository\BlogArticleRepository;
use App\Repository\BlogCategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    /**
     * @var \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    private $blogArticleRepository;
    /**
     * @var \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    private $blogCategoryRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->blogArticleRepository = $entityManager->getRepository('App:BlogArticle');
        $this->blogCategoryRepository = $entityManager->getRepository('App:BlogCategory');
    }

    #[Route('/article', name: 'article')]
    public function index(): Response
    {
        //dump($this->blogArticleRepository->findAll());
        return $this->render('article/articleIndex.html.twig', [
            'categories' => $this->blogCategoryRepository->findAll(),
            'articles' => $this->blogArticleRepository->findAll()
        ]);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    #[Route('/article/{name}', name: 'article_category')]
    public function articleByCategory($name): Response
    {

        $categoryID = $this->blogCategoryRepository->getCategoryName($name);
        return $this->render('article/category.html.twig', [
            'articlesByCategory' => $this->blogArticleRepository->showByCategory($categoryID),
        ]);
    }

    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/createCategory', name: 'createCategory')]
    public function newCategory(Request $request, ManagerRegistry $doctrine): Response
    {
        $category = new BlogCategory();
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Category!'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $manager = $doctrine->getManager();
            $manager->persist($category);
            $manager->flush();
            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('article/newCategory.html.twig', [
            'catForm' => $form,
        ]);
    }

    #[Route('/createArticle', name: 'createArticle')]
    public function newArticle(Request $request, ManagerRegistry $doctrine): Response
    {
        $blogCategories = $this->blogCategoryRepository->findAll();

        $article = new BlogArticle();
        $form = $this->createFormBuilder($article)
            ->add('ShortDescription', TextType::class)
            ->add('LongDescription', TextareaType::class)
            ->add('Image', TextType::class)
            ->add('Created_at', DateType::class, array(
                'input' => 'datetime_immutable'))
            ->add('Author', TextType::class)
            ->add('Category', EntityType::class, [
                'class' => BlogCategory::class,
                'choice_label' => function ($blogCategories) {
                    return $blogCategories->getName();
                }
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Category!'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $manager = $doctrine->getManager();
            $manager->persist($article);
            $manager->flush();
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('homepage');
        }

        return $this->renderForm('article/newArticle.html.twig', [
            'articleForm' => $form,
        ]);
    }
}
