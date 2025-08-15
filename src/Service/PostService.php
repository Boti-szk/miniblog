<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostService{
    private PostRepository $repo;
    private EntityManagerInterface $em;

    public function __construct(PostRepository $repo, EntityManagerInterface $em){
        $this->repo = $repo;
        $this->em = $em;
    }

    public function list(): array{
        return $this->repo->findAll();
    }

    public function get(int $id): ?Post{
        return $this->repo->find($id);
    }

    public function create(string $title, string $content, string $author): Post{
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);
        $post->setAuthor($author);
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setUpdatedAt(new \DateTime());

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    public function update(Post $post, string $title, string $content, string $author): Post{
        $post->setTitle($title);
        $post->setContent($content);
        $post->setAuthor($author);
        $post->setUpdatedAt(new \DateTime());

        $this->em->flush();

        return $post;
    }

    public function delete(Post $post): void
    {
        $this->em->remove($post);
        $this->em->flush();
    }
}
