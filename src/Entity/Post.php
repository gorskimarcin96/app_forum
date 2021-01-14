<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"post"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post"})
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"post"})
     */
    private $numberEntries = 0;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @Groups({"user"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="posts")
     * @Groups({"post"})
     */
    private $tag;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=PostFile::class, mappedBy="post")
     * @Groups({"file"})
     */
    private $postFiles;

    /**
     * @ORM\OneToMany(targetEntity=PostComment::class, mappedBy="post")
     */
    private $postComments;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->tag = new ArrayCollection();
        $this->postFiles = new ArrayCollection();
        $this->postComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNumberEntries()
    {
        return $this->numberEntries;
    }

    public function setNumberEntries($numberEntries): self
    {
        $this->numberEntries = $numberEntries;

        return $this;
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
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|PostFile[]
     */
    public function getPostFiles(): Collection
    {
        return $this->postFiles;
    }

    public function addPostFile(PostFile $postFile): self
    {
        if (!$this->postFiles->contains($postFile)) {
            $this->postFiles[] = $postFile;
            $postFile->setPost($this);
        }

        return $this;
    }

    public function removePostFile(PostFile $postFile): self
    {
        if ($this->postFiles->removeElement($postFile)) {
            // set the owning side to null (unless already changed)
            if ($postFile->getPost() === $this) {
                $postFile->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PostComment[]
     */
    public function getPostComments(): Collection
    {
        return $this->postComments;
    }

    public function addPostComment(PostComment $postComment): self
    {
        if (!$this->postComments->contains($postComment)) {
            $this->postComments[] = $postComment;
            $postComment->setPost($this);
        }

        return $this;
    }

    public function removePostComment(PostComment $postComment): self
    {
        if ($this->postComments->removeElement($postComment)) {
            // set the owning side to null (unless already changed)
            if ($postComment->getPost() === $this) {
                $postComment->setPost(null);
            }
        }

        return $this;
    }
}
