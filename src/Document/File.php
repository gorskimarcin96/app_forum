<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PreFlushEventArgs;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\DiscriminatorField;
use Doctrine\ODM\MongoDB\Mapping\Annotations\DiscriminatorMap;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Index;
use Doctrine\ODM\MongoDB\Mapping\Annotations\InheritanceType;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @Document
 * @InheritanceType("SINGLE_COLLECTION")
 * @DiscriminatorField("type")
 * @DiscriminatorMap({"postFile"=PostFile::class, "postCommentFile"=PostCommentFile::class})
 * @HasLifecycleCallbacks
 */
abstract class File
{
    public const PUBLIC_UPLOAD_DIR = '/upload';
    public const ROOT_UPLOAD_DIR = '/public/upload';

    /**
     * @MongoDB\Id
     * @Groups({"file"})
     */
    private $id;

    /**
     * @Field(type="string")
     * @Groups({"file"})
     */
    private $name;

    /**
     * @Field(type="string")
     * @Index(unique=true, order="asc")
     */
    private $hash;

    /**
     * @Field(type="string")
     */
    private $extension;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return $this
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param $extension
     */
    public function setExtension($extension): void
    {
        $this->extension = $extension;
    }

    /**
     * @Groups({"file"})
     * @return string
     */
    public function getPath(): string
    {
        return self::PUBLIC_UPLOAD_DIR . '/' . $this->getHash() . '.' . $this->getExtension();
    }

    /**
     * @MongoDB\PreFlush()
     * @param PreFlushEventArgs $args
     */
    public function autoSetHash(PreFlushEventArgs $args): void
    {
        $this->setHash(hash('md5', 'file_' . $this->getId()));

        $em = $args->getDocumentManager();
        $em->persist($this);
    }

    /**
     * @MongoDB\PreRemove()
     */
    public function removeFile(LifecycleEventArgs $eventArgs): void
    {
        //todo i have to somehow inject root_dir
        unlink(__dir__ . '/../../public' . $eventArgs->getDocument()->getPath());
    }
}