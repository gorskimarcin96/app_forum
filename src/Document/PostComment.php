<?php

namespace App\Document;

use App\Repository\PostCommentRepository;
use App\Utils\DateManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;
use MongoDB\BSON\Timestamp;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Document(repositoryClass=PostCommentRepository::class)
 * @HasLifecycleCallbacks
 */
class PostComment
{
    use Validator;
    /**
     * @Id
     * @Groups({"post_comment"})
     */
    private $id;

    /**
     * @Field(type="string")
     * @Assert\NotBlank(message="The description cannot be empty.")
     * @Assert\Length(min=10, minMessage="Description must be at least {{ limit }} characters long.")
     * @Groups({"post_comment"})
     */
    private $description;

    /**
     * @Groups({"post_comment"})
     * @ReferenceOne(targetDocument=Post::class, cascade={"persist"})
     */
    private $post;

    /**
     * @ReferenceOne(targetDocument=User::class, cascade={"persist"})
     * @Groups({"user"})
     */
    private $user;

    /**
     * @Field(type="timestamp")
     */
    private $createdAt;

    /**
     * @Field(type="timestamp")
     */
    private $updatedAt;

    /**
     * @ReferenceMany(targetDocument=PostCommentFile::class, mappedBy="postComment")
     * @Groups({"file"})
     */
    private $files;

    /**
     * PostComment constructor.
     */
    public function __construct()
    {
        $this->createdAt = new Timestamp(0, 0);
        $this->updatedAt = new Timestamp(0, 0);
        $this->files = new ArrayCollection();
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
     * @return Post|null
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post|null $post
     * @return $this
     */
    public function setPost(?Post $post): self
    {
        $this->post = $post;

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
    public function getFiles(): Collection
    {
        return $this->files;
    }

    /**
     * @param PostCommentFile $postCommentFile
     * @return $this
     */
    public function addFile(PostCommentFile $postCommentFile): self
    {
        if (!$this->files->contains($postCommentFile)) {
            $this->files[] = $postCommentFile;
            $postCommentFile->setPostComment($this);
        }

        return $this;
    }

    /**
     * @param PostCommentFile $postCommentFile
     * @return $this
     */
    public function removeFile(PostCommentFile $postCommentFile): self
    {
        if ($this->files->removeElement($postCommentFile) && $postCommentFile->getPostComment() === $this) {
            $postCommentFile->setPostComment(null);
        }

        return $this;
    }

    /**
     * @return string
     * @Groups({"post_comment"})
     */
    public function getFormatCreatedAt(): string
    {
        return date(DateManager::DATETIME_FORMAT, $this->getCreatedAt()->getTimestamp());
    }
}
