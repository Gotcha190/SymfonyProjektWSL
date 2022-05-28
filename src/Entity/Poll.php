<?php

namespace App\Entity;

use App\Repository\PollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Entity(repositoryClass: PollRepository::class)]
class Poll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    protected ?string $question;

    #[ORM\ManyToMany(targetEntity: 'PollAnswer' , cascade: ['persist'])]
    protected ?Collection $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
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

    /**
     * @return Collection<int, PollAnswer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

//    /**
//     * @param PollAnswer $answers
//     * @return $this
//     */
//    public function setAnswers(PollAnswer $answers): self
//    {
//        $this->answers = $answers;
//
//        return $this;
//    }

    /**
     * @param PollAnswer $answer
     * @return void
     */
    public function addAnswer(PollAnswer $answer): void
    {
        //$answer->addPoll($this);
        if(!$this->answers->contains($answer)){
            $this->answers->add($answer);
        }
    }
    public function removeAnswer(PollAnswer $answer): void
    {
        if(!$this->answers->contains($answer)){
            $this->answers->remove($answer);
        }
    }
}
