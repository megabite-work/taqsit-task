<?php

namespace App\Action\Post;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class DeleteAction
{
    public function __construct(private EntityManagerInterface $em, private Security $security) {}

    public function __invoke(int $id): array
    {
        $post = $this->em->getRepository(Post::class)->findOneBy(['id' => $id, 'user' => $this->security->getUser()]);

        if ($post === null) {
            return ['success' => false, 'message' => 'Post not found'];
        }

        $this->em->remove($post);

        return ['success' => true, 'message'=> 'Post successfully deleted'];
    }
}
