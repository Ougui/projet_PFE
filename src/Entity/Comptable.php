<?php

namespace App\Entity;

use App\Repository\ComptableRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComptableRepository::class)
 */
class Comptable extends Employe
{
   /**
     * @ORM\OneToOne(targetEntity=Employe::class, inversedBy="type", cascade={"persist", "remove"})
     */
    protected $type;

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
