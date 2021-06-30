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
     * @ORM\Column(type="float",options={"default" : 0})
     */
    private $panier;

    /**
     * @ORM\Column(type="float",options={"default" : 0})
     */
    private $cotisations;

    /**
     * @ORM\Column(type="float",options={"default" : 0})
     */
    private $impots;

    /**
     * @ORM\Column(type="float")
     */
    private $Total;

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

    public function getPanier(): ?float
    {
        return $this->panier;
    }

    public function setPanier(float $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getCotisations(): ?float
    {
        return $this->cotisations;
    }

    public function setCotisations(float $cotisations): self
    {
        $this->cotisations = $cotisations;

        return $this;
    }

    public function getImpots(): ?float
    {
        return $this->impots;
    }

    public function setImpots(float $impots): self
    {
        $this->impots = $impots;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->Total;
    }

    public function setTotal(float $Total): self
    {
        $this->Total = $Total;

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
