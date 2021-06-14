<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeRepository::class)
 */
class Employe extends User
{
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $prenom;

    /**
     * @ORM\Column(type="date_immutable")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieuNaissance;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroTelephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ccp;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $SituationFamiliale;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $nombreEnfant;

    /**
     * @ORM\Column(type="float")
     */
    private $Salaire_de_base;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="type", cascade={"persist", "remove"})
     */
    private $type;
    /**
     * @ORM\OneToOne(targetEntity=Rh::class, mappedBy="type", cascade={"persist", "remove"})
     */
    /**
     * @ORM\OneToOne(targetEntity=Comptable::class, mappedBy="type", cascade={"persist", "remove"})
     */
    /**
     * @ORM\OneToOne(targetEntity=DirecteurGeneral::class, mappedBy="type", cascade={"persist", "remove"})
     */
    /**
     * @ORM\ManyToOne(targetEntity=Filiale::class, inversedBy="employes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $filiale;

    /**
     * @ORM\OneToMany(targetEntity=Bulletin::class, mappedBy="employe")
     */
    private $bulletin;

    /**
     * @ORM\OneToMany(targetEntity=Presence::class, mappedBy="employe", orphanRemoval=true)
     */
    private $presence;

    /**
     * @ORM\ManyToOne(targetEntity=Poste::class, inversedBy="employes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $poste;

    public function __construct()
    {
        $this->bulletin = new ArrayCollection();
        $this->presence = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeImmutable $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumeroTelephone(): ?string
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(string $numeroTelephone): self
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function getCcp(): ?string
    {
        return $this->ccp;
    }

    public function setCcp(string $ccp): self
    {
        $this->ccp = $ccp;

        return $this;
    }

    public function getSituationFamiliale(): ?string
    {
        return $this->SituationFamiliale;
    }

    public function setSituationFamiliale(string $SituationFamiliale): self
    {
        $this->SituationFamiliale = $SituationFamiliale;

        return $this;
    }

    public function getNombreEnfant(): ?int
    {
        return $this->nombreEnfant;
    }

    public function setNombreEnfant(?int $nombreEnfant): self
    {
        $this->nombreEnfant = $nombreEnfant;

        return $this;
    }

    public function getSalaireDeBase(): ?float
    {
        return $this->Salaire_de_base;
    }

    public function setSalaireDeBase(float $Salaire_de_base): self
    {
        $this->Salaire_de_base = $Salaire_de_base;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getFiliale(): ?Filiale
    {
        return $this->filiale;
    }

    public function setFiliale(?Filiale $filiale): self
    {
        $this->filiale = $filiale;

        return $this;
    }

    /**
     * @return Collection|Bulletin[]
     */
    public function getBulletin(): Collection
    {
        return $this->bulletin;
    }

    public function addBulletin(Bulletin $bulletin): self
    {
        if (!$this->bulletin->contains($bulletin)) {
            $this->bulletin[] = $bulletin;
            $bulletin->setEmploye($this);
        }

        return $this;
    }

    public function removeBulletin(Bulletin $bulletin): self
    {
        if ($this->bulletin->removeElement($bulletin)) {
            // set the owning side to null (unless already changed)
            if ($bulletin->getEmploye() === $this) {
                $bulletin->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Presence[]
     */
    public function getPresence(): Collection
    {
        return $this->presence;
    }

    public function addPresence(Presence $presence): self
    {
        if (!$this->presence->contains($presence)) {
            $this->presence[] = $presence;
            $presence->setEmploye($this);
        }

        return $this;
    }

    public function removePresence(Presence $presence): self
    {
        if ($this->presence->removeElement($presence)) {
            // set the owning side to null (unless already changed)
            if ($presence->getEmploye() === $this) {
                $presence->setEmploye(null);
            }
        }

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

        return $this;
    }
}
