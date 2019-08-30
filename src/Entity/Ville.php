<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

// Valentin Et Jeremy

// ajout des Assert pour nom et code postal (Jeremy)
//nom : seulement lettres autorisées
//code postale : 5 chiffres en tout
// ajout des not null

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 */
class Ville
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Regex(
     *     pattern     = "/^[a-z ]+$/i",
     *     match=true,
     *     message="Le nom de la ville ne peut pas contenir de numéros ou de caractères spéciaux"
     *     )
     * @Assert\NotNull(
     * message="Un nom de ville ne peut pas être null"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     *
     *       @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "un code postale a 5 chiffres",
     *      maxMessage = "un code postale a 5 chiffres",
     *      exactMessage = "un code postale a 5 chiffres"
     * )
     * @Assert\NotNull(
     * message="Un coe postal ne peut pas être null"
     * )
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lieu", mappedBy="nomVille", orphanRemoval=true)
     */
    private $lieux;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sortie", mappedBy="ville")
     */
    private $sorties;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }



    //Getter et Setter

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

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection|Lieu[]
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    public function addLieux(Lieu $lieux): self
    {
        if (!$this->lieux->contains($lieux)) {
            $this->lieux[] = $lieux;
            $lieux->setNomVille($this);
        }

        return $this;
    }

    public function removeLieux(Lieu $lieux): self
    {
        if ($this->lieux->contains($lieux)) {
            $this->lieux->removeElement($lieux);
            // set the owning side to null (unless already changed)
            if ($lieux->getNomVille() === $this) {
                $lieux->setNomVille(null);
            }
        }

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
            $sorty->setVille($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->contains($sorty)) {
            $this->sorties->removeElement($sorty);
            // set the owning side to null (unless already changed)
            if ($sorty->getVille() === $this) {
                $sorty->setVille(null);
            }
        }

        return $this;
    }
}
