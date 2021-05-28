<?php

namespace App\Entity;

use App\Repository\LoginFormRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoginFormRepository::class)
 */
class LoginForm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="external_login_form")
     */
    private $users_exteral_login_form;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="active_login_form")
     */
    private $users_active_login_form;

    public function __construct()
    {
        $this->users_exteral_login_form = new ArrayCollection();
        $this->users_active_login_form = new ArrayCollection();
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
}
