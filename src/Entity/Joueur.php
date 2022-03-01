<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\JoueurRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *  attributes={"order"={"id"="ASC"},"pagination_items_per_page"=30},
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
 *      "groups"={"joueur:get"}
 *  },
 *  denormalizationContext={
 *       "groups"={"joueur:get"}
 *  }
 * )
 * @ApiFilter(SearchFilter::class, properties={"nom"="exact","prenom"="exact", "avatar"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"id"="ASC"})
 * @ApiFilter(NumericFilter::class, properties={"equipe.id", "id"})
 * @ORM\Entity(repositoryClass=JoueurRepository::class)
 */



class Joueur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"joueur:get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"joueur:get"}) 
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"joueur:get"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"joueur:get"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"joueur:get"})
     */
    private $pointDefense;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"joueur:get"})
     */
    private $pointAttaque;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"joueur:get"})
     */
    private $pointVitesse;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"joueur:get"})
     */
    private $pointEndurence;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"joueur:get"})
     */
    private $nbVictoire;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"joueur:get"})
     */
    private $nbDefaite;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"joueur:get"})
     */
    private $numJoueurEquipe;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class, inversedBy="joueurs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"joueur:get"})
     */
    private $equipe;


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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getPointDefense(): ?int
    {
        return $this->pointDefense;
    }

    public function setPointDefense(int $pointDefense): self
    {
        $this->pointDefense = $pointDefense;

        return $this;
    }

    public function getPointAttaque(): ?int
    {
        return $this->pointAttaque;
    }

    public function setPointAttaque(int $pointAttaque): self
    {
        $this->pointAttaque = $pointAttaque;

        return $this;
    }

    public function getPointVitesse(): ?int
    {
        return $this->pointVitesse;
    }

    public function setPointVitesse(int $pointVitesse): self
    {
        $this->pointVitesse = $pointVitesse;

        return $this;
    }

    public function getPointEndurence(): ?int
    {
        return $this->pointEndurence;
    }

    public function setPointEndurence(int $pointEndurence): self
    {
        $this->pointEndurence = $pointEndurence;

        return $this;
    }

    public function getNbVictoire(): ?int
    {
        return $this->nbVictoire;
    }

    public function setNbVictoire(int $nbVictoire): self
    {
        $this->nbVictoire = $nbVictoire;

        return $this;
    }

    public function getNbDefaite(): ?int
    {
        return $this->nbDefaite;
    }

    public function setNbDefaite(int $nbDefaite): self
    {
        $this->nbDefaite = $nbDefaite;

        return $this;
    }

    public function getNumJoueurEquipe(): ?int
    {
        return $this->numJoueurEquipe;
    }

    public function setNumJoueurEquipe(int $numJoueurEquipe): self
    {
        $this->numJoueurEquipe = $numJoueurEquipe;

        return $this;
    }

    public function getEquipe(): ?equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?equipe $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }
}
