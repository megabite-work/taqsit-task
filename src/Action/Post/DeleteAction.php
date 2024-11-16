<?php

namespace App\Action\Post;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class DeleteAction
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private RequestStack $requestStack
    ) {}

    public function __invoke(int $id): Response
    {
        $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id, 'user' => $this->security->getUser()]);

        if ($post === null) {
            $this->requestStack->getSession()->getFlashBag()->add('error', 'Post not found.');

            return new RedirectResponse('/');
        }

        $this->em->remove($post);
        $this->em->flush();
        $this->requestStack->getSession()->getFlashBag()->add('success', 'Post successfully deleted.');

        return new RedirectResponse('/');
    }
}
