<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(length: 50)]
    private ?string $auteur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $texte = null;

    #[ORM\ManyToOne(inversedBy: 'fk_categories')]
    private ?Categories $FK_categories = null;

    #[ORM\ManyToOne(inversedBy: 'fk_team')]
    private ?Team $FK_team = null;

    #[ORM\OneToMany(mappedBy: 'FK_articles', targetEntity: Commentaires::class)]
    private Collection $fk_articles;

    #[ORM\ManyToOne(inversedBy: 'fk_articles')]
    private ?User $FK_user = null;

    public function __construct()
    {
        $this->fk_articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(string $texte): static
    {
        $this->texte = $texte;

        return $this;
    }

    public function getFKCategories(): ?Categories
    {
        return $this->FK_categories;
    }

    public function setFKCategories(?Categories $FK_categories): static
    {
        $this->FK_categories = $FK_categories;

        return $this;
    }

    public function getFKTeam(): ?Team
    {
        return $this->FK_team;
    }

    public function setFKTeam(?Team $FK_team): static
    {
        $this->FK_team = $FK_team;

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getFkArticles(): Collection
    {
        return $this->fk_articles;
    }

    public function addFkArticle(Commentaires $fkArticle): static
    {
        if (!$this->fk_articles->contains($fkArticle)) {
            $this->fk_articles->add($fkArticle);
            $fkArticle->setFKArticles($this);
        }

        return $this;
    }

    public function removeFkArticle(Commentaires $fkArticle): static
    {
        if ($this->fk_articles->removeElement($fkArticle)) {
            // set the owning side to null (unless already changed)
            if ($fkArticle->getFKArticles() === $this) {
                $fkArticle->setFKArticles(null);
            }
        }

        return $this;
    }

    public function getFKUser(): ?User
    {
        return $this->FK_user;
    }

    public function setFKUser(?User $FK_user): static
    {
        $this->FK_user = $FK_user;

        return $this;
    }
}
