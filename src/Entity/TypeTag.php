<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TypeTag
 *
 * @ORM\Table(name="type_tag")
 * @ORM\Entity
 */
class TypeTag {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="colour", type="string", length=255, nullable=true)
     */
    private $colour;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="created_by", type="integer", nullable=false)
     */
    private $createdBy;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_on", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdOn = 'CURRENT_TIMESTAMP';

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="type")
     * @ORM\JoinColumn(name="tags", referencedColumnName="id_type")
     */
    private Collection $tags;

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getColour(): ?string {
        return $this->colour;
    }

    public function setColour(?string $colour): self {
        $this->colour = $colour;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getCreatedBy(): ?int {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): self {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface {
        return $this->createdOn;
    }

    public function setCreatedOn(?\DateTimeInterface $createdOn): self {
        $this->createdOn = $createdOn;

        return $this;
    }


}
