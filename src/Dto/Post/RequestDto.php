<?php

namespace App\Dto\Post;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class RequestDto
{
	public function __construct(
		#[Groups(['post:create', 'post:update'])]
		#[Assert\NotBlank(groups: ['post:create'])]
		#[Assert\Length(max: 255)]
		public ?string $title,
		#[Groups(['post:create', 'post:update'])]
		#[Assert\NotBlank(groups: ['post:create'])]
		public ?string $body,
		#[Groups(['post:create', 'post:update'])]
		#[Assert\NotBlank(groups: ['post:create'])]
		#[Assert\Image(maxSize: '10M')]
		public ?UploadedFile $image,
	) {}
}