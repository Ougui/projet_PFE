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
    private $type;
}
