<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * Resource
 *
 * @ORM\Table(name="resource", indexes={@ORM\Index(name="id_folder", columns={"id_folder"})})
 * @ORM\Entity
 */
class Resource {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private ?string $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private ?string $filename;

    /**
     * @var Folder
     *
     * @ORM\ManyToOne(targetEntity="Folder", inversedBy="content")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_folder", referencedColumnName="id")
     * })
     */
    private Folder $folder;

    /**
     * @var Collection
     *
     * Many Resources have Many Tags.
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="resource_tag",
     *      joinColumns={@ORM\JoinColumn(name="id_resource", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_tag", referencedColumnName="id")}
     * )
     */
    private Collection $tags;

    public ?string $url = NULL;


    #[Pure]
    public function __construct() {
        $this->tags = new ArrayCollection();
    }


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

    public function getFilename(): ?string {
        return $this->filename;
    }

    public function setFilename(?string $filename): self {
        $this->filename = $filename;
        return $this;
    }

    public function getFolder(): ?Folder {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): self {
        $this->folder = $folder;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection {
        return $this->tags;
    }

    /**
     * @param Collection $tags
     * @return Resource
     */
    public function setTags(Collection $tags): Resource {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return Resource
     */
    public function setUrl(?string $url): Resource {
        $this->url = $url;
        return $this;
    }
}
