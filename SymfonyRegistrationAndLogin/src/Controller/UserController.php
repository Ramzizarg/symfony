<?php
// src/Controller/UserController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('backutilisateur.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        if ($user->isBlocked()) {
            return $this->render('blockuser.html.twig');
        }


        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get roles from the form data
            $roles = $form->get('roles')->getData();

            // Set the roles in the User entity
            $user->setRoles($roles);

            $entityManager->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users/{id}/block', name: 'block_user')]
    public function block(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Toggle the blocked status
        $user->setBlocked(!$user->isBlocked());

        $entityManager->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/users/{id}/unblock', name: 'unblock_user')]
    public function unblock(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Set the blocked status to false for unblocking
        $user->setBlocked(false);

        $entityManager->flush();

        return $this->redirectToRoute('user_list');
    }

    #[Route('/blocked-user-message', name: 'blocked_user_message')]
    public function blockedUserMessage(): Response
    {
        return $this->render('blockuser.html.twig');
    }

    #[Route('/users/{id}/delete', name: 'user_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('user_list');
    }
}
