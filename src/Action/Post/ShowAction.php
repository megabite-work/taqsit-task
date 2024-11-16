<?php

namespace App\Action\Post;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class ShowAction
{
    public function __construct(
        private PostRepository $postRepository,
        private RequestStack $requestStack
    ) {}

    public function __invoke(int $id): array|Response
    {
        $post = $this->postRepository->findWithUser($id);

        if ($post === null) {
            $this->requestStack->getSession()->getFlashBag()->add('error', 'Post not found.');

            return new RedirectResponse('/');
        }

        return ['post' => $post];
    }
}
