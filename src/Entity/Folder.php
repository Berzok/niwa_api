<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * Folder
 *
 * @ORM\Table(name="folder", indexes={@ORM\Index(name="id_parent_folder", columns={"id_parent_folder"})})
 * @ORM\Entity
 */
class Folder {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @var int
     *
     * @ORM\Column(name="depth", type="integer", nullable=false)
     */
    private int $depth = 0;

    /**
     * @var ?Folder
     *
     * @ORM\ManyToOne(targetEntity="Folder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_parent_folder", referencedColumnName="id")
     * })
     */
    private ?Folder $parent_folder = NULL;

    /**
     * @var Folder|null
     *
     * @ORM\OneToMany(targetEntity="Folder", mappedBy="parent_folder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_children_folder", referencedColumnName="id")
     * })
     */
    private ?Collection $children_folder = NULL;

    /**
     * @var Collection|null
     *
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="folder")
     */
    private ?Collection $content;


    #[Pure]
    public function __construct() {
        $this->content = new ArrayCollection();
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

    public function getDepth(): ?int {
        return $this->depth;
    }

    public function setDepth(int $depth): self {
        $this->depth = $depth;
        return $this;
    }

    public function getParentFolder(): ?self {
        return $this->parent_folder;
    }

    public function setParentFolder(?self $parent_folder): self {
        $this->parent_folder = $parent_folder;
        return $this;
    }

    public function getChildrenFolder(): ?Collection {
        return $this->children_folder;
    }

    public function setChildrenFolder(?self $children_folder): self {
        $this->parent_folder = $children_folder;
        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getContent(): Collection|null {
        return $this->content;
    }

    /**
     * @param Collection|null $content
     * @return Folder
     */
    public function setContent(Collection|null $content): Folder {
        $this->content = $content;
        return $this;
    }

}
