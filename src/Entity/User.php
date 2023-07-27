<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'FK_user', targetEntity: Commentaires::class)]
    private Collection $fk_user;

    #[ORM\OneToMany(mappedBy: 'FK_user', targetEntity: Articles::class)]
    private Collection $fk_articles;

    public function __construct()
    {
        $this->fk_user = new ArrayCollection();
        $this->fk_articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getFkUser(): Collection
    {
        return $this->fk_user;
    }

    public function addFkUser(Commentaires $fkUser): static
    {
        if (!$this->fk_user->contains($fkUser)) {
            $this->fk_user->add($fkUser);
            $fkUser->setFKUser($this);
        }

        return $this;
    }

    public function removeFkUser(Commentaires $fkUser): static
    {
        if ($this->fk_user->removeElement($fkUser)) {
            // set the owning side to null (unless already changed)
            if ($fkUser->getFKUser() === $this) {
                $fkUser->setFKUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Articles>
     */
    public function getFkArticles(): Collection
    {
        return $this->fk_articles;
    }

    public function addFkArticle(Articles $fkArticle): static
    {
        if (!$this->fk_articles->contains($fkArticle)) {
            $this->fk_articles->add($fkArticle);
            $fkArticle->setFKUser($this);
        }

        return $this;
    }

    public function removeFkArticle(Articles $fkArticle): static
    {
        if ($this->fk_articles->removeElement($fkArticle)) {
            // set the owning side to null (unless already changed)
            if ($fkArticle->getFKUser() === $this) {
                $fkArticle->setFKUser(null);
            }
        }

        return $this;
    }
}
