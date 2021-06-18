<?php

namespace App\Entity;

use App\Repository\DirecteurGeneralRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class DirecteurGeneral
 * @package App\Entity
 * @ORM\MappedSuperclass
 * @ORM\Entity(repositoryClass=DirecteurGeneralRepository::class)
 */
class DirecteurGeneral extends Employe
{

}
