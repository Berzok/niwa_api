<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 */
class Role
{
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
     * @var int|null
     *
     * @ORM\Column(name="can_create", type="integer", nullable=true)
     */
    private $canCreate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="can_update", type="integer", nullable=true)
     */
    private $canUpdate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="can_delete", type="integer", nullable=true)
     */
    private $canDelete;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCanCreate(): ?int
    {
        return $this->canCreate;
    }

    public function setCanCreate(?int $canCreate): self
    {
        $this->canCreate = $canCreate;

        return $this;
    }

    public function getCanUpdate(): ?int
    {
        return $this->canUpdate;
    }

    public function setCanUpdate(?int $canUpdate): self
    {
        $this->canUpdate = $canUpdate;

        return $this;
    }

    public function getCanDelete(): ?int
    {
        return $this->canDelete;
    }

    public function setCanDelete(?int $canDelete): self
    {
        $this->canDelete = $canDelete;

        return $this;
    }


}
