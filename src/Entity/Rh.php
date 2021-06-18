<?php

namespace App\Entity;

use App\Repository\RHRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rh
 * @package App\Entity
 * @ORM\MappedSuperclass
 * @ORM\Entity(repositoryClass=RHRepository::class)
 */
class Rh extends Employe
{

    public function __toString()
    {
        return $this->getNom().'';
    }




}
