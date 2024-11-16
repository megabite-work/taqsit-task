<?php

namespace App\Action\Auth;

use App\Dto\Auth\LoginDto;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginAction
{
    public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function __invoke(LoginDto $dto): Response
    {
        $user = $this->userRepository->findByEmail($dto->email);
        $this->requestStack->getSession()->set('data', ['email' => $dto->email]);
        $this->requestStack->getSession()->getFlashBag()->clear();
        
        if ($user === null) {
            $this->requestStack->getSession()->getFlashBag()->add('error', 'You are not registered, please register.');

            return new RedirectResponse('/register');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $dto->password)) {
            $this->requestStack->getSession()->getFlashBag()->add('error', 'Invalid email or password.');

            return new RedirectResponse('/login');
        }

        $this->requestStack->getSession()->getFlashBag()->add('success', 'You have successfully logged in.');
        $this->security->login($user);

        return new RedirectResponse('/');
    }
}
