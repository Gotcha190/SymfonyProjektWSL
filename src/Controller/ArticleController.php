<?php

namespace App\Controller;

use App\Entity\BlogArticle;
use App\Entity\BlogCategory;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\BlogArticleRepository;
use App\Repository\BlogCategoryRepository;
use App\Repository\CommentRepository;
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
use Symfony\Component\Security\Core\Security;

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

    /**
     * @var \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    private $commentRepository;

    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->blogArticleRepository = $entityManager->getRepository('App:BlogArticle');
        $this->blogCategoryRepository = $entityManager->getRepository('App:BlogCategory');
        $this->commentRepository = $entityManager->getRepository('App:Comment');
        $this->security = $security;
    }

    #[Route('/article', name: 'article')]
    public function index(Request $request): Response
    {
        //dump($this->blogArticleRepository->findAll());
        return $this->render('article/articleIndex.html.twig', [
            'categories' => $this->blogCategoryRepository->findAll(),
            'articles' => $this->blogArticleRepository->findAll(),
        ]);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    #[Route('/article/{category}', name: 'article_category')]
    public function articleByCategory($category): Response
    {

        $categoryID = $this->blogCategoryRepository->getCategoryName($category);
        return $this->render('article/category.html.twig', [
            'articlesByCategory' => $this->blogArticleRepository->showByCategory($categoryID),
        ]);
    }

    #[Route('/article/{category}/{name}', name: 'article_full')]
    public function articleFullView($name, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($this->blogArticleRepository->findOneBy(['ShortDescription' => $name]));
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('homepage');
        }
        return $this->render('article/articlefullview.html.twig', [
            'article' => $this->blogArticleRepository->findOneBy(['ShortDescription' => $name]),
            'comment_form' => $form->createView()
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
