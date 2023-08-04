<?php

namespace App\Controller\Admin;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use App\Repository\TeamRepository;
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

    #[Route('/new/{id}', name: 'app_articles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService, TeamRepository $teamRepository, $publicUploadDir, $id): Response
    {
        //TODO remplir fk_team_id lors du new article
        $article = new Articles();
        $team = $teamRepository->find($id);
        $article->setFKTeam($team);
        $format = 'Y-m-d';
        $date = date('Y-m-d');
        $date = DateTime::createFromFormat($format, $date);
        $article->setDate($date);

        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->fileUpload($article, $form, $fileUploaderService, $publicUploadDir);
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
    public function edit(Request $request, Articles $article, EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService, $publicUploadDir, $publicDeleteFileDir): Response
    {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //TODO pour effacer le fichier dans le dossier /public/uploads
            $file = $form['logo']->getData();

            if ($file) {
                $uow = $entityManager->getUnitOfWork();
                $originalData = $uow->getOriginalEntityData($article);
                $logo = explode('/', $originalData['logo']);
                @unlink($publicDeleteFileDir . '/' . $logo[2]);
                $file_name = $fileUploaderService->upload($file);
                if (null !== $file_name) {
                    $full_path = $publicUploadDir . '/' . $file_name;
                }

                $article->setLogo($full_path);
            }

            $this->fileUpload($article, $form, $fileUploaderService, $publicUploadDir);
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

    private function fileUpload($article, $form, $fileUploaderService, $publicUploadDir): void
    {
        $data = $form['logo']->getData();

        if ($data) {
            $fileName = $fileUploaderService->upload($data);
            $filePath = $publicUploadDir . '/' . $fileName;
            $article->setLogo($filePath);
        }
    }
}
