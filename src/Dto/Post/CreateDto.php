<?php

namespace App\Dto\Post;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateDto
{
	public function __construct(
		#[Assert\NotBlank]
		#[Assert\Length(max: 255)]
		public string $title,
		#[Assert\NotBlank]
		public string $body,
		public UploadedFile $image,
	) {}
}
