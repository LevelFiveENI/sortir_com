<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;



// ajout des Assert (Jeremy)
// nom : seulement lettres et chiffres autorisées
// nbInscriptionsMax : (entre 2 et 999)
// dateHeureDebut : (+2 jours à -d'un an avant)
// duree : ne peut etre inf a 0
// infos : seulement lettres et chiffres autorisées
// date limite d'inscription : mini demain
// ajout des not null
// ajout de la contrainte date limite d'inscription < date heure debut
// (installation du bundle pour la property path)
// composer require symfony/property-access





/**
 * @ORM\Entity(repositoryClass="App\Repository\SortieRepository")
 */
class Sortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     *
     *  @Assert\Regex(
     *  pattern     = "/^[a-z0-9 éè']+$/i",
     *  match=true,
     *  message="Le nom de la sortie ne peut pas contenir de caractères spéciaux"
     *    )
     * @Assert\NotNull(
     * message="Un nom de sortie ne peut pas être null"
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\Range(
     *      min = "+2 days",
     *      max = "+1 year",
     *     minMessage = "On ne peut pas créer une sortie a moins de deux jours",
     *     maxMessage = "On ne peut pas créer une sortie plus d'un an avant"
     * )
     * @Assert\NotNull(
     * message="La date de début ne peut pas être null"
     * )
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Range(
     *  min = "0",
     *  minMessage = "l'évènement ne peut pas etre négatif"
     *  )
     * @Assert\NotNull(
     * message="La durée ne peut pas être null"
     * )
     */
    private $duree;

    /**
     * @ORM\Column(type="date")
     *
     * @Assert\Range(
     *      min = "+1 days",
     *
     *     minMessage = "La date limite ne peut pas etre avant demain",
     * )
     * @Assert\NotNull(
     * message="La date de la limite d'inscription ne peut pas être null"
     * )
     *
     * @Assert\LessThan(
     *     propertyPath="dateHeureDebut",
     *   message = "La date limite d'inscription ne peut pas être supérieure à la date de début"
     * )
     *
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Range(
     *      min = "2",
     *      max = "999",
     *     minMessage = "il faut au moins 2 participants",
     *     maxMessage = "il ne peut pas y avoir plus de 999 participants"
     * )
     * @Assert\NotNull(
     * message="Le nombre d'inscription max. ne peut pas être null"
     * )
     *
     */

    private $nbInscriptionsMax;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Regex(
     *  pattern     = "/^[a-z0-9 éè']+$/i",
     *  match=true,
     *  message="Les infos concernant la sortie ne peuvent pas contenir de caracteres spéciaux"
     *    )
     * @Assert\NotNull(
     * message="Les infos ne peuvent pas être null"
     * )
     */
    private $infosSortie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="sorties", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ville", inversedBy="sorties")
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="Organisateur")
     */
    private $Organisateur;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="Participant")
     */
    private $Participant;

    public function __construct()
    {
        $this->Participant = new ArrayCollection();
    }

    //----Getter et Setter de Sortie

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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(string $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->Organisateur;
    }

    public function setOrganisateur(?User $Organisateur): self
    {
        $this->Organisateur = $Organisateur;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipant(): Collection
    {
        return $this->Participant;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->Participant->contains($participant)) {
            $this->Participant[] = $participant;
            $participant->addParticipant($this);
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->Participant->contains($participant)) {
            $this->Participant->removeElement($participant);
            $participant->removeParticipant($this);
        }

        return $this;
    }

}
