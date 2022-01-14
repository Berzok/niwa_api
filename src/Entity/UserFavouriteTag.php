<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserFavouriteTag
 *
 * @ORM\Table(name="user_favourite_tag")
 * @ORM\Entity
 */
class UserFavouriteTag
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
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_tag", type="integer", nullable=false)
     */
    private $idTag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdTag(): ?int
    {
        return $this->idTag;
    }

    public function setIdTag(int $idTag): self
    {
        $this->idTag = $idTag;

        return $this;
    }


}
