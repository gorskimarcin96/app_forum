<?php

namespace App\Entity;

use App\Repository\PostCommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostCommentRepository::class)
 */
class PostComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"post_comment"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post_comment"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="postComments")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="postComments")
     * @Groups({"user"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post_comment"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post_comment"})
     */
    private $updatedAt;
    
    /**
     * @ORM\OneToMany(targetEntity=PostCommentFile::class, mappedBy="postComment")
     * @Groups({"file"})
     */
    private $postCommentFiles;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->postCommentFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPostCommentFiles(): Collection
    {
        return $this->postCommentFiles;
    }

    public function addPostCommentFile(PostCommentFile $postCommentFile): self
    {
        if (!$this->postCommentFiles->contains($postCommentFile)) {
            $this->postCommentFiles[] = $postCommentFile;
            $postCommentFile->setPostComment($this);
        }

        return $this;
    }

    public function removePostCommentFile(PostCommentFile $postCommentFile): self
    {
        if ($this->postCommentFiles->removeElement($postCommentFile)) {
            // set the owning side to null (unless already changed)
            if ($postCommentFile->getPostComment() === $this) {
                $postCommentFile->setPostComment(null);
            }
        }

        return $this;
    }
}
