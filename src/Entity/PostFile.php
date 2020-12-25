<?php

namespace App\Entity;

use App\Repository\PostFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostFileRepository::class)
 */
class PostFile extends File
{
    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="postFiles")
     */
    private $post;

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}