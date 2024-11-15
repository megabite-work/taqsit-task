<?php

namespace App\Action\Post;

use App\Dto\Post\RequestDto;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

final class UpdateAction
{
    public function __construct(private EntityManagerInterface $em, private Security $security) {}

    public function __invoke(int $id, RequestDto $dto): array
    {
        try {
            $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id, 'user' => $this->security->getUser()]);

            if ($post === null) {
                return ['success' => false, 'message' => 'Post not found'];
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

            return ['success' => true, 'message' => 'Post successfully updated'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => 'Post update failed'];
        }
    }
}
