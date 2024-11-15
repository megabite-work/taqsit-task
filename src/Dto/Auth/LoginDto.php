<?php

namespace App\Dto\Auth;

use Symfony\Component\Validator\Constraints as Assert;

final class LoginDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        public string $password,
    ) {}
}
