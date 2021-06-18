<?php

namespace App\Entity;

use App\Repository\DirecteurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Directeur
 * @package App\Entity
 * @ORM\MappedSuperclass
 * @ORM\Entity(repositoryClass=DirecteurRepository::class)
 */
class Directeur extends Employe
{


}
