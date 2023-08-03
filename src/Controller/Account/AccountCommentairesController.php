<?php

namespace App\Controller\Account;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\ArticlesRepository;
use App\Repository\CommentairesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('account/commentaires')]
class AccountCommentairesController extends AbstractController
{
    #[Route('/', name: 'app_account_commentaires_index', methods: ['GET'])]
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('account/commentaires/index.html.twig', [
            'commentaires' => $commentairesRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_account_commentaires_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ArticlesRepository $articlesRepository, $id): Response
    {
        $commentaire = new Commentaires();
        $user = $this->getUser();
        $article = $articlesRepository->find($id);
        $commentaire->setFKUser($user);
        $commentaire->setFKArticles($article);
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_account_commentaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/commentaires/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_commentaires_show', methods: ['GET'])]
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('account/commentaires/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/mes_commentaires/{id}', name: 'app_account_mes_commentaires_show', methods: ['GET'])]
    public function showMyComment(UserRepository $userRepository, CommentairesRepository $commentairesRepository, $id): Response
    {
        $user = $userRepository->find($id);
        $idUser = $user->getId();
        $commentaires = $commentairesRepository->findAll();
        $mesCommentaires = [];

        foreach ($commentaires as $commentaire) {
            if ($commentaire->getFKUser()->getId() === $idUser) {
                $mesCommentaires[] = $commentaire;
            }
        }

        return $this->render('account/commentaires/show.html.twig', [
            'commentaires' => $mesCommentaires,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_account_commentaires_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaires $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_account_commentaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/commentaires/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_commentaires_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaires $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_account_commentaires_index', [], Response::HTTP_SEE_OTHER);
    }
}
