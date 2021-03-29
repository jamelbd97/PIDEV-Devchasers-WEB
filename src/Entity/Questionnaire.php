<?php

namespace App\Entity;

use App\Repository\QuestionnaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=QuestionnaireRepository::class)
 */
class Questionnaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="questionnaire", cascade={"remove"})
     */
    private $question;

    /**
     * @ORM\OneToOne(targetEntity=CandidatureMission::class, mappedBy="questionnaire", cascade={"persist", "remove"})
     */
    private $candidatureMission;

    /**
     * @ORM\OneToOne(targetEntity=Mission::class, inversedBy="questionnaire", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Mission;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $description;

    public function __construct()
    {
        $this->question = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(question $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question[] = $question;
            $question->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeQuestion(question $question): self
    {
        if ($this->question->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuestionnaire() === $this) {
                $question->setQuestionnaire(null);
            }
        }

        return $this;
    }

    public function getCandidatureMission(): ?CandidatureMission
    {
        return $this->candidatureMission;
    }

    public function setCandidatureMission(?CandidatureMission $candidatureMission): self
    {
        // unset the owning side of the relation if necessary
        if ($candidatureMission === null && $this->candidatureMission !== null) {
            $this->candidatureMission->setQuestionnaire(null);
        }

        // set the owning side of the relation if necessary
        if ($candidatureMission !== null && $candidatureMission->getQuestionnaire() !== $this) {
            $candidatureMission->setQuestionnaire($this);
        }

        $this->candidatureMission = $candidatureMission;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->Mission;
    }

    public function setMission(Mission $Mission): self
    {
        $this->Mission = $Mission;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}