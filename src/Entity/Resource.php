<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="Folder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_folder", referencedColumnName="id")
     * })
     */
    private Folder $folder;

    public ?string $url = NULL;


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

}
