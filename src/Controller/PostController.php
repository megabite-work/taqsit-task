<?php

namespace App\Controller;

use App\Action\Post\CreateAction;
use App\Action\Post\DeleteAction;
use App\Action\Post\IndexAction;
use App\Action\Post\ShowAction;
use App\Action\Post\UpdateAction;
use App\Dto\Post\RequestDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(IndexAction $action): Response
    {
        return $this->render('index.html.twig', $action());
    }

    #[Route('/{id<\d+>}', name: 'app_post_show', methods: ['GET'])]
    public function show(int $id, ShowAction $action): Response
    {
        return $this->render('post/show.html.twig', $action($id));
    }

    #[Route('/create', name: 'app_post_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('post/create.html.twig');
    }

    #[Route('/{id<\d+>}/edit', name: 'app_post_edit', methods: ['GET'])]
    public function edit(): Response
    {
        return $this->render('post/edit.html.twig');
    }

    #[Route('/', name: 'app_post_store', methods: ['POST'])]
    public function store(#[MapRequestPayload(serializationContext: ['groups' => ['post:create']])] RequestDto $dto, CreateAction $action): Response
    {
        return $this->redirectToRoute('app_home', $action($dto));
    }

    #[Route('/{id<\d+>}', name: 'app_post_update', methods: ['PATCH', 'PUT'])]
    public function update(#[MapRequestPayload(serializationContext: ['groups' => ['post:update']])] RequestDto $dto, UpdateAction $action, int $id): Response
    {
        return $this->redirectToRoute('app_home', $action($id, $dto));
    }

    #[Route('/{id<\d+>}', name: 'app_post_delete', methods: ['DELETE'])]
    public function delete(DeleteAction $action, int $id): Response
    {
        return $this->redirectToRoute('app_home', $action($id));
    }
}
