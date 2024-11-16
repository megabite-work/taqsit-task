<?php

namespace App\Action\Post;

use App\Dto\Post\RequestDto;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class UpdateAction
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private RequestStack $requestStack
    ) {}

    public function __invoke(int $id, RequestDto $dto): Response
    {
        try {
            $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id, 'user' => $this->security->getUser()]);

            if ($post === null) {
                $this->requestStack->getSession()->getFlashBag()->add('error', 'Post not found.');

                return new RedirectResponse('/');
            }

            if ($dto->title && $dto->title !== $post->getTitle()) {
                $post->setTitle($dto->title);
            }
            if ($dto->body && $dto->body !== $post->getBody()) {
                $post->setBody($dto->body);
            }
            if ($dto->image) {
                $image = uniqid() . '.' . $dto->image->guessExtension();
                $dto->image->move('media', $image);
                $fs = new Filesystem();
                if ($fs->exists($post->getImage())) $fs->remove($post->getImage());
                $post->setImage("media/$image");
            }

            $this->em->flush();
            $this->requestStack->getSession()->getFlashBag()->add('success', 'Post successfully updated.');

            return new RedirectResponse("/$id");
        } catch (Throwable $e) {
            $this->requestStack->getSession()->getFlashBag()->add('error', 'Post update failed.');
            
            return new RedirectResponse("/$id/edit");
        }
    }
}
