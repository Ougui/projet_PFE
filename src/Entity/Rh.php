<?php

namespace App\Entity;

use App\Repository\RhRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rh
 * @package App\Entity
 * @ORM\MappedSuperclass
 * @ORM\Entity(repositoryClass=RhRepository::class)
 */
class Rh extends Employe
{

    public function __toString()
    {
        return $this->getNom().'';
    }




}
