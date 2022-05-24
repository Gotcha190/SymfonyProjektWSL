<?php

namespace App\Entity;

use App\Repository\PollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PollRepository::class)]
class Poll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $question;

    #[ORM\OneToOne(targetEntity: PollResponse::class, cascade: ['persist', 'remove'])]
    private $pollResponse;

    #[ORM\ManyToMany(targetEntity: PollAnswer::class, mappedBy: 'poll', cascade: ['persist'])]
    private $pollAnswers;

    public function __construct()
    {
        $this->pollAnswers = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->question;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getPollResponse(): ?PollResponse
    {
        return $this->pollResponse;
    }

    public function setPollResponse(?PollResponse $pollResponse): self
    {
        $this->pollResponse = $pollResponse;

        return $this;
    }

    /**
     * @return Collection<int, PollAnswer>
     */
    public function getPollAnswers(): Collection
    {
        return $this->pollAnswers;
    }

//    public function addPollAnswer(PollAnswer $pollAnswer): void
//    {
//        $this->pollAnswers->add($pollAnswer);
//    }
//
//    public function removePollAnswer(PollAnswer $pollAnswer): void
//    {
//        $this->pollAnswers->remove($pollAnswer);
//    }
    public function addPollAnswer(PollAnswer $pollAnswer): self
    {
        if (!$this->pollAnswers->contains($pollAnswer)) {
            $this->pollAnswers[] = $pollAnswer;
            $pollAnswer->addPoll($this);
        }

        return $this;
    }

    public function removePollAnswer(PollAnswer $pollAnswer): self
    {
        if ($this->pollAnswers->removeElement($pollAnswer)) {
            $pollAnswer->removePoll($this);
        }

        return $this;
    }
}
