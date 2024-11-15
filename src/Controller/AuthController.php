<?php

namespace App\Controller;

use App\Action\Auth\RegisterAction;
use App\Dto\Auth\RegisterDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_show_login', methods: ['GET'])]
    public function showLogin(): Response
    {
        return $this->render('auth/login.html.twig');
    }

    #[Route('/register', name: 'app_show_register', methods: ['GET'])]
    public function showRegister(): Response
    {
        return $this->render('auth/register.html.twig');
    }

    #[Route('/auth/login', name: 'app_login', methods: ['POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $email = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'email' => $email,
            'error' => $error,
        ]);
    }

    #[Route('/auth/register', name: 'app_register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterDto $dto, RegisterAction $action): Response
    {
        return $this->redirectToRoute('app_login', [$action($dto)]);
    }

    #[Route('/auth/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(Security $security): Response
    {
        $security->logout(false);
        return $this->redirectToRoute('app_login');
    }
}