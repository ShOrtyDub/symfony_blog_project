<?php

namespace App\Controller\Public;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class PublicUserController extends AbstractController
{
    #[Route('/public/new', name: 'app_public_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPaswword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPaswword
            );

            $user->setRoles(["ROLE_VISITOR"]);

            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

//            return $this->redirectToRoute('user_login', [], Response::HTTP_SEE_OTHER);
            return $this->render('account/login/account_login.html.twig', ['last_username' => $user->getEmail(), 'error' => []]);

        }

        return $this->render('account/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
