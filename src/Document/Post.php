<?php

namespace App\Document;

use App\Repository\PostRepository;
use App\Utils\DateManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;
use MongoDB\BSON\Timestamp;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Document(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @Id
     * @Groups({"post"})
     */
    private $id;

    /**
     * @Field(type="string")
     * @Groups({"post"})
     */
    private $title;

    /**
     * @Field(type="string")
     * @Groups({"post"})
     */
    private $description;

    /**
     * @Field(type="int")
     * @Groups({"post"})
     */
    private $numberEntries = 0;

    /**
     * @ReferenceOne(targetDocument=User::class)
     * @Groups({"user"})
     */
    private $user;

    /**
     * @ReferenceMany(targetDocument=Tag::class)
     */
    private $tag;

    /**
     * @Field(type="timestamp")
     */
    private $createdAt;

    /**
     * @Field(type="timestamp")
     */
    private $updatedAt;

    /**
     * @ReferenceMany(targetDocument=PostFile::class, mappedBy="post")
     * @Groups({"file"})
     */
    private $postFiles;

    /**
     * @ReferenceMany(targetDocument=PostComment::class, mappedBy="post")
     */
    private $postComments;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->createdAt = new Timestamp(0, 0);
        $this->updatedAt = new Timestamp(0, 0);
        $this->tag = new ArrayCollection();
        $this->postFiles = new ArrayCollection();
        $this->postComments = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumberEntries(): int
    {
        return $this->numberEntries;
    }

    /**
     * @param $numberEntries
     * @return $this
     */
    public function setNumberEntries($numberEntries): self
    {
        $this->numberEntries = $numberEntries;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Timestamp|null
     */
    public function getCreatedAt(): ?Timestamp
    {
        return $this->createdAt;
    }

    /**
     * @param Timestamp $createdAt
     * @return $this
     */
    public function setCreatedAt(Timestamp $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Timestamp|null
     */
    public function getUpdatedAt(): ?Timestamp
    {
        return $this->updatedAt;
    }

    /**
     * @param Timestamp $updatedAt
     * @return $this
     */
    public function setUpdatedAt(Timestamp $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    /**
     * @param Tag $tag
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|PostFile[]
     */
    public function getFiles(): Collection
    {
        return $this->postFiles;
    }

    /**
     * @param PostFile $postFile
     * @return $this
     */
    public function addFile(PostFile $postFile): self
    {
        if (!$this->postFiles->contains($postFile)) {
            $this->postFiles[] = $postFile;
            $postFile->setPost($this);
        }

        return $this;
    }

    /**
     * @param PostFile $postFile
     * @return $this
     */
    public function removeFile(PostFile $postFile): self
    {
        if ($this->postFiles->removeElement($postFile) && $postFile->getPost() === $this) {
            $postFile->setPost(null);
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

    /**
     * @param PostComment $postComment
     * @return $this
     */
    public function addPostComment(PostComment $postComment): self
    {
        if (!$this->postComments->contains($postComment)) {
            $this->postComments[] = $postComment;
            $postComment->setPost($this);
        }

        return $this;
    }

    /**
     * @param PostComment $postComment
     * @return $this
     */
    public function removePostComment(PostComment $postComment): self
    {
        if ($this->postComments->removeElement($postComment) && $postComment->getPost() === $this) {
            $postComment->setPost(null);
        }

        return $this;
    }

    /**
     * @return array
     * @Groups({"post"})
     */
    public function getArrayTag(): array
    {
        foreach ($this->tag->toArray() as $key => $tag) {
            $data[] = [
                'id' => $tag->getId(),
                'name' => $tag->getName()
            ];
        }

        return $data ?? [];
    }

    /**
     * @return string
     * @Groups({"post"})
     */
    public function getFormatCreatedAt(): string
    {
        return date(DateManager::DATETIME_FORMAT, $this->getCreatedAt()->getTimestamp());
    }

    /**
     * @return int
     * @Groups({"post"})
     */
    public function getCountComment(): int
    {
        return $this->getPostComments()->count();
    }
}
