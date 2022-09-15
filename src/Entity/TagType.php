<?php

namespace App\Entity;

use App\Repository\TagTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagTypeRepository::class)
 */
class TagType {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $is_unique;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getIsUnique(): ?bool {
        return $this->is_unique;
    }

    public function setIsUnique(bool $is_unique): self {
        $this->is_unique = $is_unique;
        return $this;
    }
}
