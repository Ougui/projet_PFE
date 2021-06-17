<?php

namespace App\Entity;

use App\Repository\DirecteurGeneralRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DirecteurGeneralRepository::class)
 */
class DirecteurGeneral extends Employe
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
