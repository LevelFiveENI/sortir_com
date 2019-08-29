<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


// ajout des Assert pour nom et rue (Jeremy)
// seulement lettres et chiffres autorisées
// ajout des not null


/**
 * Valentin et Jeremy
 * @ORM\Entity(repositoryClass="App\Repository\LieuRepository")
 */
class Lieu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *   pattern     = "/^[a-z0-9 ]+$/i",
     *   match=true,
     *   message="Le nom du lieu ne peut pas contenir de caractères spéciaux"
     *     )
     * @Assert\NotNull(
     * message="Un nom de lieu ne peut pas être null"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Regex(
     *   pattern     = "/^[a-z0-9 ]+$/i",
     *   match=true,
     *   message="La rue ne peut pas contenir de caractères spéciaux"
     *     )
     * @Assert\NotNull(
     * message="Un nom de rue ne peut pas être null"
     * )
     *
     */
    private $rue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="lieux")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nomVille;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="lieu", orphanRemoval=true)
     */
    private $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }




    //Getter et Setter de Lieu-------------------------------

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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getNomVille(): ?Ville
    {
        return $this->nomVille;
    }

    public function setNomVille(?Ville $nomVille): self
    {
        $this->nomVille = $nomVille;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setLieu($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->contains($sorty)) {
            $this->sorties->removeElement($sorty);
            // set the owning side to null (unless already changed)
            if ($sorty->getLieu() === $this) {
                $sorty->setLieu(null);
            }
        }

        return $this;
    }
}
