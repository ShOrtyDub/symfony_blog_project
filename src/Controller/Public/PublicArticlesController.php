<?php

namespace App\Controller\Public;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Service\FileUploaderService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('account/public/articles')]
class PublicArticlesController extends AbstractController
{
    #[Route('/list/{FK_categories?}', name: 'app_public_articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository, $FK_categories): Response
    {
        if ($FK_categories === null) {
            return $this->render('public/articles/index.html.twig', [
                'articles' => $articlesRepository->findAll(),
            ]);
        } else {
            return $this->render('public/articles/index.html.twig', [
                'articles' => $articlesRepository->findBy(['FK_categories' => $FK_categories]),

            ]);
        }

    }

    #[Route('/{id}', name: 'app_public_articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('public/articles/show.html.twig', [
            'article' => $article,
            'categorie' => $article->getFKCategories()->getNom()
        ]);
    }

}
