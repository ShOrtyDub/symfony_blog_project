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

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'FK_categories', targetEntity: Articles::class)]
    private Collection $fk_categories;

    public function __construct()
    {
        $this->fk_categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getFkCategories(): Collection
    {
        return $this->fk_categories;
    }

    public function addFkCategory(Articles $fkCategory): static
    {
        if (!$this->fk_categories->contains($fkCategory)) {
            $this->fk_categories->add($fkCategory);
            $fkCategory->setFKCategories($this);
        }

        return $this;
    }

    public function removeFkCategory(Articles $fkCategory): static
    {
        if ($this->fk_categories->removeElement($fkCategory)) {
            // set the owning side to null (unless already changed)
            if ($fkCategory->getFKCategories() === $this) {
                $fkCategory->setFKCategories(null);
            }
        }

        return $this;
    }
}
