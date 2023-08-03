<?php

namespace App\Entity;

use App\Repository\CommentairesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentairesRepository::class)]
class Commentaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $auteur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeure = null;

    #[ORM\Column(length: 255)]
    private ?string $texte = null;

    #[ORM\Column(length: 100)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(inversedBy: 'fk_user')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $FK_user = null;

    #[ORM\ManyToOne(inversedBy: 'fk_articles')]
    private ?Articles $FK_articles = null;

    #[ORM\Column]
    private ?bool $status = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTimeInterface $dateHeure): static
    {
        $this->dateHeure = $dateHeure;

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

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
        $this->commentaire = $commentaire;

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

    public function getFKArticles(): ?Articles
    {
        return $this->FK_articles;
    }

    public function setFKArticles(?Articles $FK_articles): static
    {
        $this->FK_articles = $FK_articles;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }
}
