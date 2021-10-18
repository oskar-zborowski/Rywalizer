<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Username::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=16, unique=true, nullable=true)
     */
    private $profile_picture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $joining_date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_blocked;

    /**
     * @ORM\OneToOne(targetEntity=RefreshToken::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $refreshToken;

    /**
     * @ORM\OneToOne(targetEntity=Password::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Username::class, mappedBy="user", orphanRemoval=true)
     */
    private $usernames;

    public function __construct()
    {
        $this->usernames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?Username
    {
        return $this->login;
    }

    public function setLogin(Username $username): self
    {
        $this->login = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): ?string
    {
        return $this->getLogin()->getUsername();
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profile_picture;
    }

    public function setProfilePicture(?string $profile_picture): self
    {
        $this->profile_picture = $profile_picture;

        return $this;
    }

    public function getJoiningDate(): ?\DateTimeInterface
    {
        return $this->joining_date;
    }

    public function setJoiningDate(\DateTimeInterface $joining_date): self
    {
        $this->joining_date = $joining_date;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getIsBlocked(): ?bool
    {
        return $this->is_blocked;
    }

    public function setIsBlocked(bool $is_blocked): self
    {
        $this->is_blocked = $is_blocked;

        return $this;
    }

    public function getRefreshToken(): ?RefreshToken
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(RefreshToken $refreshToken): self
    {
        // set the owning side of the relation if necessary
        if ($refreshToken->getUser() !== $this) {
            $refreshToken->setUser($this);
        }

        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getPassword(): ?Password
    {
        return $this->password;
    }

    public function setPassword(Password $password): self
    {
        // set the owning side of the relation if necessary
        if ($password->getUser() !== $this) {
            $password->setUser($this);
        }

        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Username[]
     */
    public function getUsernames(): Collection
    {
        return $this->usernames;
    }

    public function addUsername(Username $username): self
    {
        if (!$this->usernames->contains($username)) {
            $this->usernames[] = $username;
            $username->setUser($this);
        }

        return $this;
    }

    public function removeUsername(Username $username): self
    {
        if ($this->usernames->removeElement($username)) {
            // set the owning side to null (unless already changed)
            if ($username->getUser() === $this) {
                $username->setUser(null);
            }
        }

        return $this;
    }
}