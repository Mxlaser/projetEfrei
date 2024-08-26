<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\User;
use App\Form\ArticlesType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $article = $doctrine->getRepository(Articles::class)->findAll();
        return $this->render('articles/articles.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/add', name: 'article_add')]
    public function add(ManagerRegistry $doctrine,  Request $request)
    {
        $user = $this->getUser();
        if (!$user instanceof User || (!$user->hasRole('ADMIN')) && !$user->hasRole('USER')) {
            return $this->redirectToRoute('app_login');
        }
        $entityManager = $doctrine->getManager();
        $article = new Articles();

        $article->setDateCreation(new \DateTimeImmutable());

        $formArticle = $this->createForm(ArticlesType::class, $article);

        $formArticle->handleRequest($request);
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {

            $user = $this->getUser();
            $article->setUser($user);

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', "L'article a bien été ajoutée !");

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/form-add.html.twig', ['formArticle' => $formArticle->createView()]);
    }

    #[Route('/articles/show/{id}', name: 'articles_show')]
    public function show(ManagerRegistry $doctrine, $id)
    {
        $article = $doctrine->getRepository(Articles::class)->find($id);
        return $this->render('articles/show.html.twig', ["article" => $article]);
    }

    #[Route('/articles/edit/{id}', name: 'articles_edit')]
    public function update(ManagerRegistry $doctrine, int $id, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User || (!$user->hasRole('ADMIN')) && !$user->hasRole('USER')) {
            return $this->redirectToRoute('app_login');
        }

        $entityManager = $doctrine->getManager();
        $article = $doctrine->getRepository(Articles::class)->find($id);
        $article->setDateModification(new \DateTimeImmutable());

        $formArticle = $this->createForm(ArticlesType::class, $article);

        $formArticle->handleRequest($request);
        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/form-edit.html.twig', ['formArticle' => $formArticle->createView()]);
    }

    #[Route('/articles/delete/{id}', name: 'articles_delete')]
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $user = $this->getUser();
        if (!$user instanceof User || (!$user->hasRole('ADMIN')) && !$user->hasRole('USER')) {
            return $this->redirectToRoute('app_login');
        }

        $article = $doctrine->getRepository(Articles::class)->find($id);
        $entityManager = $doctrine->getManager();

        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('app_articles');
    }
}
