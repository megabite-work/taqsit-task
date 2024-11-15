<?php

namespace App\Action\Post;

use App\Dto\Post\RequestDto;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Throwable;

final class CreateAction
{
    public function __construct(private EntityManagerInterface $em, private Security $security) {}

    public function __invoke(RequestDto $dto): array
    {
        try {
            $image = uniqid() . '.' . $dto->image->guessExtension();
            $dto->image->move('media', $image);
            $post = (new Post())
                ->setTitle($dto->title)
                ->setUser($this->security->getUser())
                ->setBody($dto->body)
                ->setImage("media/$image");
            $this->em->persist($post);
            $this->em->flush();

            return ['success' => true, 'post' => $post, 'message' => 'Post successfully created'];
        } catch (Throwable $e) {
            return ['success' => false, 'message' => 'Post create failed'];
        }
    }
}
