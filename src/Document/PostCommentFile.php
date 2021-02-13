<?php

namespace App\Document;

use App\Repository\PostCommentFileRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @Document(repositoryClass=PostCommentFileRepository::class)
 */
class PostCommentFile extends File
{
    /**
     * @ReferenceOne(targetDocument=PostComment::class, inversedBy="postCommentFiles")
     */
    private $postComment;

    /**
     * @return PostComment|null
     */
    public function getPostComment(): ?PostComment
    {
        return $this->postComment;
    }

    /**
     * @param PostComment|null $postComment
     * @return $this
     */
    public function setPostComment(?PostComment $postComment): self
    {
        $this->postComment = $postComment;

        return $this;
    }
}