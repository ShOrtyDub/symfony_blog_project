<?php

namespace App\Controller\Account;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('account/categories')]
class AccountCategoriesController extends AbstractController
{
    #[Route('/', name: 'app_account_categories_index', methods: ['GET'])]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('account/categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_account_categories_show', methods: ['GET'])]
    public function show(Categories $category): Response
    {
        return $this->render('account/categories/show.html.twig', [
            'category' => $category,
        ]);
    }
}
