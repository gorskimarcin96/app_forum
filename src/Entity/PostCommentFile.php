<?php

namespace App\Entity;

use App\Repository\PostCommentFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostCommentFileRepository::class)
 */
class PostCommentFile extends File
{
    /**
     * @ORM\ManyToOne(targetEntity=PostComment::class, inversedBy="postCommentFiles")
     */
    private $postComment;

    public function getPostComment(): ?PostComment
    {
        return $this->postComment;
    }

    public function setPostComment(?PostComment $postComment): self
    {
        $this->postComment = $postComment;

        return $this;
    }
}