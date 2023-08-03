<?php

namespace App\Controller\Public;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('account/public/categories')]
class PublicCategoriesController extends AbstractController
{
    #[Route('/', name: 'app_public_categories_index', methods: ['GET'])]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('public/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_public_categories_show', methods: ['GET'])]
    public function show(Categories $category): Response
    {
        return $this->render('public/categories/show.html.twig', [
            'category' => $category,
        ]);
    }
}
