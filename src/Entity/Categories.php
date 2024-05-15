<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_categories = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategories(): ?string
    {
        return $this->nom_categories;
    }

    public function setNomCategories(string $nom_categories): static
    {
        $this->nom_categories = $nom_categories;

        return $this;
    }
}
