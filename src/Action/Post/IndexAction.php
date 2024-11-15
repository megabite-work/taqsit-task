<?php

namespace App\Action\Post;

use App\Repository\PostRepository;

final class IndexAction
{
    public function __construct(private PostRepository $postRepository) {}

    public function __invoke(): array
    {
        return ['success' => true, 'data' => $this->postRepository->findAll()];
    }
}
