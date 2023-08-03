<?php

namespace App\Controller\Public;

use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('account/public/commentaires')]
class PublicCommentairesController extends AbstractController
{
    #[Route('/', name: 'app_public_commentaires_index', methods: ['GET'])]
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('public/commentaires/index.html.twig', [
            'commentaires' => $commentairesRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_public_commentaires_show', methods: ['GET'])]
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('public/commentaires/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }
}
