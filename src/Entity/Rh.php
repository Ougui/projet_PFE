<?php

namespace App\Entity;

use App\Repository\RHRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RHRepository::class)
 */
class Rh extends Employe
{
    /**
     * @ORM\OneToOne(targetEntity=Employe::class, inversedBy="type", cascade={"persist", "remove"})
     */
    protected $type;


    public function __toString()
    {
        return $this->getNom().'';
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
