<?php

namespace App\Entity;

use App\Entity\Trait\DatesTrait;
use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[Vich\Uploadable]
#[ORM\EntityListeners(['App\EntityListener\MediaListener'])]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: "products", fileNameProperty: "path")]
    private ?File $file = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    use DatesTrait;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /** @see \Serializable::serialize() */
    public function __serialize()
    {
        return [
            "id" => $this->id,
            "path" => $this->path
        ];
    }

    /** @see \Serializable::unserialize() */
    public function __unserialize($serialized)
    {
        $this->id = $serialized['id'];
        $this->path = $serialized['path'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFile(?File $file = null): static
    {
        $this->file = $file;

        if($file) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function __toString()
    {
        return $this->path;
    }
}
