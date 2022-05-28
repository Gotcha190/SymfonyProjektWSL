<?php

namespace App\Entity;

use App\Repository\PollAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PollAnswerRepository::class)]
class PollAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $text;

    #[ORM\ManyToMany(targetEntity: Poll::class, cascade: ['persist'])]
    private $polls;

    public function __construct()
    {
        $this->polls = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
    public function addPoll(Poll $poll): void
    {
        if(!$this->polls->contains($poll)){
            $this->polls->add($poll);
        }
    }

    /**
     * @return Collection<int, Poll>
     */
    public function getPolls(): Collection
    {
        return $this->polls;
    }

    public function removePoll(Poll $poll): self
    {
        $this->polls->removeElement($poll);

        return $this;
    }
}
