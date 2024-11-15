<?php

namespace App\Action\Auth;

use App\Dto\Auth\RegisterDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterAction
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $em,
        private UserRepository $repo
    ) {
    }

    public function __invoke(RegisterDto $dto): array
    {
        $user = (new User())
            ->setEmail($dto->email);
        $this->hashPassword($user, $dto->password);
        $this->em->persist($user);
        $this->em->flush();

        return compact('user');
    }

    private function hashPassword(User $user, string $password): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
    }
}
