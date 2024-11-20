<?php

namespace App\Dto\Post;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class RequestDto
{
	#[Groups(['post:update'])]
	#[Assert\NotBlank(groups: ['post:update'])]
	#[Assert\Positive(groups: ['post:update'])]
	public ?int $id;
	#[Groups(['post:create', 'post:update'])]
	#[Assert\NotBlank(groups: ['post:create'])]
	#[Assert\Length(max: 255, maxMessage: 'This title is too long. It should have {{ limit }} characters or less.')]
	public ?string $title;
	#[Groups(['post:create', 'post:update'])]
	#[Assert\NotBlank(groups: ['post:create'])]
	public ?string $body;
	#[Groups(['post:create', 'post:update'])]
	#[Assert\NotBlank(groups: ['post:create'])]
	#[Assert\Image(maxSize: '10M', groups: ['post:create'])]
	public ?UploadedFile $image;
}
