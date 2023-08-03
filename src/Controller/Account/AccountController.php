<?php

namespace App\Controller\Account;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/profil/{id}', name: 'app_account_profil')]
    public function profil(User $user): Response
    {
        return $this->render('account/profil/index.html.twig', [
            'user' => $user,
        ]);
    }

    //TODO
    #[Route('/profil/{id}/edit', name: 'app_account_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
//        $user = $userRepository->find($security->getUser()->getId());
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPaswword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPaswword
            );

            $user->setPassword($hashedPassword);
            $user->setRoles(["ROLE_VISITOR"]);

            $entityManager->flush();

            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/profil/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_profil_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaires $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_account_commentaires_index', [], Response::HTTP_SEE_OTHER);
    }


}
