<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/home', name: 'app_admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        /** @var User $user */
        $user = $this->getUser();

        // Check if the user is blocked
        if ($user->isBlocked()) {
            // If the user is blocked, render a blocked user message or redirect to another route
            return $this->render("blockuser.html.twig");
        }

        // Continue with the existing logic for role-based rendering
        if ($this->isGranted('ROLE_ADMIN')) {
            // If the user has the ROLE_ADMIN role, render the admin template
            return $this->render("backuser.html.twig");
        } elseif ($user->isVerified()) {
            // If the user is verified but not an admin, render the regular user template
            return $this->render("home.html.twig");
        } else {
            // If the user is not verified, render the "please-verify-email" template
            return $this->render("admin/please-verify-email.html.twig");
        }
    }
}
