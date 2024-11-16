<?php

namespace App\Action\Post;

use App\Dto\Post\RequestDto;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class CreateAction
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private RequestStack $requestStack
    ) {}

    public function __invoke(RequestDto $dto): Response
    {
        try {
            $image = uniqid() . '.' . $dto->image->guessExtension();
            $dto->image->move('media/', $image);
            $post = (new Post())
                ->setTitle($dto->title)
                ->setUser($this->security->getUser())
                ->setBody($dto->body)
                ->setImage("media/$image");
            $this->em->persist($post);
            $this->em->flush();
            $this->requestStack->getSession()->getFlashBag()->add('success', 'Post successfully created.');

            return new RedirectResponse('/');
        } catch (Throwable $e) {
            $this->requestStack->getSession()->getFlashBag()->add('error', 'Post create failed.');
            $this->requestStack->getSession()->set('data', ['title' => $dto->title, 'body' => $dto->body]);
            return new RedirectResponse('/create');
        }
    }
}
