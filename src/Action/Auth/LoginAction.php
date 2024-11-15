<?php

namespace App\Action\Auth;

use App\Dto\Auth\LoginDto;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class LoginAction
{
    public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private RequestStack $requestStack,
    ) {}

    public function __invoke(LoginDto $dto): Response
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if ($user === null) {
            $this->requestStack->getSession()->set('data', ['email' => $dto->email]);
            $this->requestStack->getSession()->getFlashBag()->add('danger', 'You are not registered, please register.');

            return new RedirectResponse('/register');
        }

        $this->security->login($user);
        $this->requestStack->getSession()->getFlashBag()->add('success', 'You have successfully logged in.');

        return new RedirectResponse('/');
    }
}
