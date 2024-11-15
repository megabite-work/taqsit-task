<?php

namespace App\Dto\Auth;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: 'email', message: 'This email already exists', entityClass: User::class)]
final class RegisterDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6, minMessage: 'This password is too short. It should have {{ limit }} characters or more.', maxMessage: 'This password is too long. It should have {{ limit }} characters or less.')]
        public string $password,
    ) {}
}
