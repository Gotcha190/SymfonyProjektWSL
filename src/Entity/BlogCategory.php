<?php

namespace App\Entity;

use App\Repository\BlogCategoryRepository;
use Doctrine\Common\{Collections\ArrayCollection, Collections\Collection};
use Doctrine\ORM\{Mapping as ORM, Mapping\Column, Mapping\GeneratedValue, Mapping\Id, Mapping\OneToMany};

#[ORM\Entity(repositoryClass: BlogCategoryRepository::class)]
class BlogCategory
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private $id;

    #[Column(type: 'string', length: 255)]
    private $name;

    #[OneToMany(mappedBy: 'Category', targetEntity: BlogArticle::class)]
    private $Products;

    public function __construct()
    {
        $this->Products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, BlogArticle>
     */
    public function getProducts(): Collection
    {
        return $this->Products;
    }

    public function addProduct(BlogArticle $product): self
    {
        if (!$this->Products->contains($product)) {
            $this->Products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(BlogArticle $product): self
    {
        if ($this->Products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
