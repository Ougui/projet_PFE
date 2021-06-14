<?php

namespace App\Entity;

use App\Repository\BulletinRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BulletinRepository::class)
 */
class Bulletin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\Column(type="float",options={"default" : 0})
     */
    private $TotalHeureSupp;

    /**
     * @ORM\Column(type="float",options={"default" : 0})
     */
    private $TotalHeureAbs;

    /**
     * @ORM\Column(type="float")
     */
    private $IEP;

    /**
     * @ORM\Column(type="float")
     */
    private $allocationFamiliale;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="bulletin")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getTotalHeureSupp(): ?float
    {
        return $this->TotalHeureSupp;
    }

    public function setTotalHeureSupp(float $TotalHeureSupp): self
    {
        $this->TotalHeureSupp = $TotalHeureSupp;

        return $this;
    }

    public function getTotalHeureAbs(): ?float
    {
        return $this->TotalHeureAbs;
    }

    public function setTotalHeureAbs(float $TotalHeureAbs): self
    {
        $this->TotalHeureAbs = $TotalHeureAbs;

        return $this;
    }

    public function getIEP(): ?float
    {
        return $this->IEP;
    }

    public function setIEP(float $IEP): self
    {
        $this->IEP = $IEP;

        return $this;
    }

    public function getAllocationFamiliale(): ?float
    {
        return $this->allocationFamiliale;
    }

    public function setAllocationFamiliale(float $allocationFamiliale): self
    {
        $this->allocationFamiliale = $allocationFamiliale;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }
}
