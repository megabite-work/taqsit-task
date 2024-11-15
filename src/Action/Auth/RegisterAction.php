<?php

namespace App\Action\Auth;

use App\Dto\Auth\RegisterDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterAction
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $em,
        private RequestStack $requestStack,
    ) {}

    public function __invoke(RegisterDto $dto): Response
    {
        try {
            $user = (new User())
                ->setEmail($dto->email);
            $this->hashPassword($user, $dto->password);
            $this->em->persist($user);
            $this->em->flush();
            $this->requestStack->getSession()->set('email', $dto->email);
            $this->requestStack->getSession()->set('password', $dto->password);
            $this->requestStack->getSession()->getFlashBag()->add('success', 'You have successfully registered in.');

            return new RedirectResponse('/login');
        } catch (\Throwable $th) {
            $this->requestStack->getSession()->set('email', $dto->email);
            $this->requestStack->getSession()->getFlashBag()->add('error', 'Register failed.');

            return new RedirectResponse('/register');
        }
    }

    private function hashPassword(User $user, string $password): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
    }
}
