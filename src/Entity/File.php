<?php

namespace App\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"postFile"="PostFile", "postCommentFile"="PostCommentFile"})
 * @ORM\HasLifecycleCallbacks()
 */
abstract class File
{
    public const PUBLIC_UPLOAD_DIR = '/upload';
    public const ROOT_UPLOAD_DIR = '/public/upload';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"file"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"file"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $extension;

    public function __construct()
    {
        $this->hash = '';
    }

    /**
     * @ORM\PostPersist
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->setHash(hash('md5', 'file_' . $args->getObject()->getId()));

        $em = $args->getObjectManager();
        $em->persist($this);
        $em->flush();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setExtension($extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @Groups({"file"})
     */
    public function getPath(): string
    {
        return self::PUBLIC_UPLOAD_DIR . '/' . $this->getHash() . '.' . $this->getExtension();
    }
}
