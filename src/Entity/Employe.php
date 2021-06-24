<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Employe
 * @package App\Entity
 * @ORM\MappedSuperclass
 * @ORM\Entity(repositoryClass=EmployeRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"employe" = "Employe","rh"="Rh","directeurgeneral"="DirecteurGeneral","comptable"="Comptable","directeur"="Directeur"})
 */
class Employe extends User
{
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $prenom;

    /**
     * @ORM\Column(type="date_immutable")
     */
    protected $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $lieuNaissance;

    /**
     * @ORM\Column(type="string", length=1)
     */
    protected $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $numeroTelephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $ccp;

    /**
     * @ORM\Column(type="string", length=1)
     */
   protected $SituationFamiliale;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $nombreEnfant;

    /**
     * @ORM\Column(type="date_immutable")
     */
    protected $dateRecrutement;


    /**
     * @ORM\ManyToOne(targetEntity=Filiale::class, inversedBy="employes")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $filiale;

    /**
     * @ORM\OneToMany(targetEntity=Bulletin::class, mappedBy="employe")
     */
   protected $bulletin;

    /**
     * @ORM\OneToMany(targetEntity=Presence::class, mappedBy="employe", orphanRemoval=true)
     */
   protected $presence;

    /**
     * @ORM\ManyToOne(targetEntity=Poste::class, inversedBy="employes")
     * @ORM\JoinColumn(nullable=false)
     */
   protected $poste;

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
    public function getDateRecrutement(): ?\DateTimeImmutable
    {
        return $this->dateRecrutement;
    }

    public function setDateRecrutement(\DateTimeImmutable $dateRecrutement): self
    {
        $this->dateRecrutement = $dateRecrutement;

        return $this;
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
