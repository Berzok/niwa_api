<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity
 */
class Tag {

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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="image_count", type="integer", nullable=true)
     */
    private $imageCount;

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
     * @var DateTime|null
     * @Serializer\Type(name="DateTime")
     *
     * @ORM\Column(name="created_on", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private ?DateTime $createdOn;

    /**
     * @var TypeTag
     *
     * @ORM\ManyToOne(targetEntity="TypeTag", inversedBy="tags", cascade={"persist"})
     * @ORM\JoinColumn(name="id_type", referencedColumnName="id")
     */
    private $type;


    public function __construct() {
        $this->createdOn = new DateTime();
    }


    public function getId(): ?int {
        return $this->id;
    }

    public function getType(): ?int {
        return $this->type;
    }

    public function setType(?int $type): self {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getImageCount(): ?int {
        return $this->imageCount;
    }

    public function setImageCount(?int $imageCount): self {
        $this->imageCount = $imageCount;

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
