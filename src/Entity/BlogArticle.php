<?php

namespace App\Entity;

use App\Repository\BlogArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: BlogArticleRepository::class)]
class BlogArticle
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private $id;

    #[Column(type: 'string', length: 255)]
    private $ShortDescription;

    #[Column(type: 'text')]
    private $LongDescription;

    #[Column(type: 'string', length: 255, nullable: true)]
    private $Image;

    #[Column(type: 'datetime_immutable')]
    private $Created_at;

    #[Column(type: 'string', length: 255, nullable: true)]
    private $Author;

    #[ManyToOne(targetEntity: BlogCategory::class, inversedBy: 'Products')]
    #[JoinColumn(nullable: false)]
    private $Category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortDescription(): ?string
    {
        return $this->ShortDescription;
    }

    public function setShortDescription(string $ShortDescription): self
    {
        $this->ShortDescription = $ShortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->LongDescription;
    }

    public function setLongDescription(string $LongDescription): self
    {
        $this->LongDescription = $LongDescription;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->Created_at;
    }

    public function setCreatedAt(DateTimeImmutable $Created_at): self
    {
        $this->Created_at = $Created_at;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(?string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getCategory(): ?BlogCategory
    {
        return $this->Category;
    }

    public function setCategory(?BlogCategory $Category): self
    {
        $this->Category = $Category;

        return $this;
    }
}
