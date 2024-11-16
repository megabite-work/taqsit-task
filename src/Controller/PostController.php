<?php

namespace App\Controller;

use App\Action\Post\CreateAction;
use App\Action\Post\DeleteAction;
use App\Action\Post\IndexAction;
use App\Action\Post\ShowAction;
use App\Action\Post\UpdateAction;
use App\Dto\Post\RequestDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function edit(int $id, ShowAction $action): Response
    {
        return $this->render('post/edit.html.twig', $action($id));
    }

    #[Route('/', name: 'app_post_store', methods: ['POST'])]
    public function store(Request $request, ValidatorInterface $validator, CreateAction $action): Response
    {
        $dto = new RequestDto($request->get('title'), $request->get('body'), $request->files->get('image'));
        $errors = $validator->validate($dto, groups: ['post:create']);
        $errors->count();

        if ($errors->count()) {
            throw new ValidationFailedException($dto, $errors);
        }

        return $action($dto);
    }

    #[Route('/{id<\d+>}/update', name: 'app_post_update', methods: ['PATCH', 'PUT', 'POST'])]
    public function update(Request $request, ValidatorInterface $validator, UpdateAction $action, int $id): Response
    {
        $dto = new RequestDto($request->get('title'), $request->get('body'), $request->files->get('image'));
        $errors = $validator->validate($dto, groups: ['post:update']);
        $errors->count();

        if ($errors->count()) {
            throw new ValidationFailedException($dto, $errors);
        }
        
        return $action($id, $dto);
    }

    #[Route('/{id<\d+>}/destroy', name: 'app_post_delete', methods: ['DELETE', 'POST'])]
    public function delete(DeleteAction $action, int $id): Response
    {
        return  $action($id);
    }
}
