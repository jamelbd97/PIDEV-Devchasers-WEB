<?php

namespace App\Entity;

use App\Repository\RevueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=RevueRepository::class)
 */
class Revue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Veuillez choisir le nombre d'etoiles")
     * @Groups("post:read")
     */
    private $nbEtoiles;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir un objet")
     * @Groups("post:read")
     */
    private $objet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir une description")
     * @Assert\Length(min=10, max=200, minMessage="Taille minimale (10)", maxMessage="Taille maximale (100) depassé")
     * @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=CandidatureOffre::class, inversedBy="revue")
     */
    private $candidatureOffre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbEtoiles(): ?int
    {
        return $this->nbEtoiles;
    }

    public function setNbEtoiles(int $nbEtoiles): self
    {
        $this->nbEtoiles = $nbEtoiles;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCandidatureOffre(): ?CandidatureOffre
    {
        return $this->candidatureOffre;
    }

    public function setCandidatureOffre(?CandidatureOffre $candidatureOffre): self
    {
        $this->candidatureOffre = $candidatureOffre;

        return $this;
    }
}