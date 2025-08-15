<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/posts')]
class PostController extends AbstractController{
    private PostService $service;

    public function __construct(PostService $service){
        $this->service = $service;
    }

    // GET /api/posts
    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse{
        $posts = $this->service->list();
        $result = array_map(fn(Post $p) => $this->toArray($p), $posts);
        return $this->json($result);
    }

    // GET /api/posts/{id}
    #[Route('/{id}', methods: ['GET'])]
    public function get(int $id): JsonResponse{
        $post = $this->service->get($id);
        if (!$post) {
            return $this->json(['error' => 'A bejegyzés nem található'], 404);
        }
        return $this->json($this->toArray($post));
    }

    // POST /api/posts
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true) ?? [];
        if(empty($data['title']) || empty($data['content']) || empty($data['author'])) {
            return $this->json(['error' => 'Hiányzó mezők (title, content, author)'], 400);
        }

        $post = $this->service->create($data['title'], $data['content'], $data['author']);
        return $this->json($this->toArray($post), 201);
    }

    // PUT /api/posts/{id}
    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse{
        $post = $this->service->get($id);
        if (!$post) {
            return $this->json(['error' => 'A bejegyzés nem található'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $post = $this->service->update(
            $post,
            $data['title'] ?? $post->getTitle(),
            $data['content'] ?? $post->getContent(),
            $data['author'] ?? $post->getAuthor()
        );

        return $this->json($this->toArray($post));
    }

    // DELETE /api/posts/{id}
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $post = $this->service->get($id);
        if (!$post) {
            return $this->json(['error' => 'A bejegyzés nem található'], 404);
        }

        $this->service->delete($post);
        return $this->json(null, 204);
    }

    //entitás -> tömb
    private function toArray($p): array
    {
        return [
            'id' => $p->getId(),
            'title' => $p->getTitle(),
            'content' => $p->getContent(),
            'author' => $p->getAuthor(),
            'createdAt' => $p->getCreatedAt()->format('Y-m-d H:i'),
            'updatedAt' => $p->getUpdatedAt()->format('Y-m-d H:i'),
        ];
    }
}
