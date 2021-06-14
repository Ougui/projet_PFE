<?php

namespace App\Entity;

use App\Repository\PosteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PosteRepository::class)
 */
class Poste
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom;

    /**
     * @ORM\Column(type="float")
     */
    private $MontantHeureSupp;

    /**
     * @ORM\Column(type="float")
     */
    private $SalaireParHeure;

    /**
     * @ORM\Column(type="integer")
     */
    private $NbJourSemaine;

    /**
     * @ORM\Column(type="integer")
     */
    private $NbHeureJour;

    /**
     * @ORM\OneToMany(targetEntity=Employe::class, mappedBy="poste")
     */
    private $employes;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMontantHeureSupp(): ?float
    {
        return $this->MontantHeureSupp;
    }

    public function setMontantHeureSupp(float $MontantHeureSupp): self
    {
        $this->MontantHeureSupp = $MontantHeureSupp;

        return $this;
    }

    public function getSalaireParHeure(): ?float
    {
        return $this->SalaireParHeure;
    }

    public function setSalaireParHeure(float $SalaireParHeure): self
    {
        $this->SalaireParHeure = $SalaireParHeure;

        return $this;
    }

    public function getNbJourSemaine(): ?int
    {
        return $this->NbJourSemaine;
    }

    public function setNbJourSemaine(int $NbJourSemaine): self
    {
        $this->NbJourSemaine = $NbJourSemaine;

        return $this;
    }

    public function getNbHeureJour(): ?int
    {
        return $this->NbHeureJour;
    }

    public function setNbHeureJour(int $NbHeureJour): self
    {
        $this->NbHeureJour = $NbHeureJour;

        return $this;
    }

    /**
     * @return Collection|Employe[]
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): self
    {
        if (!$this->employes->contains($employe)) {
            $this->employes[] = $employe;
            $employe->setPoste($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getPoste() === $this) {
                $employe->setPoste(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getNom().'';
    }
}
