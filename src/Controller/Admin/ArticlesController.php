<?php

namespace App\Controller\Admin;

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

#[Route('admin/articles')]
class ArticlesController extends AbstractController
{
    #[Route('/list/{FK_categories?}', name: 'app_articles_index', methods: ['GET'])]
    public function index(ArticlesRepository $articlesRepository, $FK_categories): Response
    {
        if ($FK_categories === null) {
            return $this->render('admin/articles/index.html.twig', [
                'articles' => $articlesRepository->findAll(),
            ]);
        } else {
            return $this->render('admin/articles/index.html.twig', [
                'articles' => $articlesRepository->findBy(['FK_categories' => $FK_categories]),

            ]);
        }

    }

    #[Route('/new', name: 'app_articles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploaderService $file_uploader, $publicUploadDir): Response
    {
        $article = new Articles();
        $format = 'Y-m-d';
        $date = date('Y-m-d');
        $date = DateTime::createFromFormat($format, $date);

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);
        $article->setDate($date);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['logo']->getData();

            if ($file !== null) {
                $fileName = $file_uploader->upload($file);
                $filePath = $publicUploadDir . '/' . $fileName;
                $article->setLogo($filePath);
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_show', methods: ['GET'])]
    public function show(Articles $article): Response
    {
        return $this->render('admin/articles/show.html.twig', [
            'article' => $article,
            'categorie' => $article->getFKCategories()->getNom()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_articles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Articles $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
    }
}
