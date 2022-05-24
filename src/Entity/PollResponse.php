<?php

namespace App\Entity;

use App\Repository\PollResponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PollResponseRepository::class)]
class PollResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $response;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponse(): ?int
    {
        return $this->response;
    }

    public function setResponse(int $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
