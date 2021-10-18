<?php

namespace App\Entity;

use App\Repository\UsernameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsernameRepository::class)
 */
class Username
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="usernames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=UsernameType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $username_type;

    /**
     * @ORM\ManyToOne(targetEntity=LoginService::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $login_service;

    /**
     * @ORM\Column(type="string", length=428, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_verified;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsernameType(): ?UsernameType
    {
        return $this->username_type;
    }

    public function setUsernameType(?UsernameType $username_type): self
    {
        $this->username_type = $username_type;

        return $this;
    }

    public function getLoginService(): ?LoginService
    {
        return $this->login_service;
    }

    public function setLoginService(?LoginService $login_service): self
    {
        $this->login_service = $login_service;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }
}
