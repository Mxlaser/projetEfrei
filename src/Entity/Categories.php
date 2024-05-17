<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Articles::class, mappedBy: 'categories')]
    private Collection $articles;

    #[ORM\ManyToMany(targetEntity: Films::class, mappedBy: 'categories')]
    private Collection $films;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->films = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Articles>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addCategory($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): static
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Films>
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function addFilm(Films $film): static
    {
        if (!$this->films->contains($film)) {
            $this->films->add($film);
            $film->addCategory($this);
        }

        return $this;
    }

    public function removeFilm(Films $film): static
    {
        if ($this->films->removeElement($film)) {
            $film->removeCategory($this);
        }

        return $this;
    }
}
