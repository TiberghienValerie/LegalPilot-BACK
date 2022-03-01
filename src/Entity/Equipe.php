<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *  attributes={"order"={"nomEquipe"="ASC"}},
 *  collectionOperations={
 *      "get",
 *      "post",
 *  },
 *  itemOperations={
 *      "get",
 *      "put",
 *      "delete",
 *  },
 *  normalizationContext={
 *      "groups"={"equipe:get"}
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"nomEquipe"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"nomEquipe"="ASC"})
 * @ApiFilter(NumericFilter::class, properties={"id"})
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */



class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"equipe:get", "joueur:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"equipe:get", "joueur:get"})
     */
    private $nomEquipe;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"equipe:get", "joueur:get"})
     */
    private $nbPartieGagne;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"equipe:get", "joueur:get"})
     */
    private $nbPartiePerdue;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"equipe:get", "joueur:get"})
     */
    private $nbPartieNull;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"equipe:get", "joueur:get"})
     */
    private $couleur;

    /**
     * @ORM\OneToMany(targetEntity=Joueur::class, mappedBy="equipe")
     */
    private $joueurs;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    public function setNomEquipe(string $nomEquipe): self
    {
        $this->nomEquipe = $nomEquipe;

        return $this;
    }

    public function getNbPartieGagne(): ?int
    {
        return $this->nbPartieGagne;
    }

    public function setNbPartieGagne(int $nbPartieGagne): self
    {
        $this->nbPartieGagne = $nbPartieGagne;

        return $this;
    }

    public function getNbPartiePerdue(): ?int
    {
        return $this->nbPartiePerdue;
    }

    public function setNbPartiePerdue(int $nbPartiePerdue): self
    {
        $this->nbPartiePerdue = $nbPartiePerdue;

        return $this;
    }

    public function getNbPartieNull(): ?int
    {
        return $this->nbPartieNull;
    }

    public function setNbPartieNull(int $nbPartieNull): self
    {
        $this->nbPartieNull = $nbPartieNull;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * @return Collection|Joueur[]
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): self
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs[] = $joueur;
            $joueur->setEquipe($this);
        }

        return $this;
    }

    public function removeJoueur(Joueur $joueur): self
    {
        if ($this->joueurs->removeElement($joueur)) {
            // set the owning side to null (unless already changed)
            if ($joueur->getEquipe() === $this) {
                $joueur->setEquipe(null);
            }
        }

        return $this;
    }
}
