<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=72, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="thumbnail", type="string", length=120, nullable=true)
     */
    private $thumbnail;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Resource", mappedBy="category")
     */
    private $resource;

    /**
     * Constructor
     */
    public function __construct() {
        $this->resource = new \Doctrine\Common\Collections\ArrayCollection();
    }

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

    public function getThumbnail(): ?string {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getResource(): Collection {
        return $this->resource;
    }

    public function addResource(Resource $resource): self {
        if (!$this->resource->contains($resource)) {
            $this->resource[] = $resource;
            $resource->addCategory($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self {
        if ($this->resource->removeElement($resource)) {
            $resource->removeCategory($this);
        }

        return $this;
    }

}
