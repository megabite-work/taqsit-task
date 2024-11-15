<?php

namespace App\Action\Post;

use App\Repository\PostRepository;

final class ShowAction
{
    public function __construct(private PostRepository $postRepository) {}

    public function __invoke(int $id): array
    {
        $post = $this->postRepository->find($id);

        if ($post === null) {
            return ['success' => false, 'message' => 'Post not found'];
        }

        return ['success' => true, 'data' => $post];
    }
}
