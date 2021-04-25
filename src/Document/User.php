<?php

namespace App\Document;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Index;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Document(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @MongoDB\Id
     * @Groups({"user"})
     */
    private $id;

    /**
     * @Field(type="string")
     * @Groups({"user"})
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Index(unique=true, order="asc")
     */
    private $email;

    /**
     * @Field(type="collection")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Field(type="string")
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @Field(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ReferenceMany(targetDocument=Post::class, mappedBy="user")
     */
    private $posts;

    /**
     * @ReferenceMany(targetDocument=PostComment::class, mappedBy="user")
     */
    private $postComments;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool $isVerified
     * @return $this
     */
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     * @return $this
     */
    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post) && $post->getUser() === $this) {
            $post->setUser(null);
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
        if (!$this->posts->contains($postComment)) {
            $this->postComments[] = $postComment;
            $postComment->setUser($this);
        }

        return $this;
    }

    /**
     * @param PostComment $postComment
     * @return $this
     */
    public function removePostComment(PostComment $postComment): self
    {
        if ($this->posts->removeElement($postComment) && $postComment->getUser() === $this) {
            $postComment->setUser(null);
        }

        return $this;
    }
}
