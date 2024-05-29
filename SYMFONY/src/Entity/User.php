<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank()]
    #[Assert\Email(message: "Votre email n'est pas valide.")]

    private ?string $email = null;

    /** 
     
*@var list<string> The user roles*/#[ORM\Column]
  private array $roles = [];

    /** 
     
*@var string The hashed password*/#[ORM\Column]
  private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre nom est trop court.',
        maxMessage: 'Votre nom est trop long, il ne doit pas dépasser 50 caractères.'
    )]
    #[Assert\Regex(
        pattern: "/\S+/",
        message: "Le champ ne peut pas contenir uniquement des espaces."
    )]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
     #[Assert\NotBlank()]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Votre nom est trop court.',
        maxMessage: 'Votre nom est trop long, il ne doit pas dépasser 50 caractères.'
    )]
    #[Assert\Regex(
        pattern: "/\S+/",
        message: "Le champ ne peut pas contenir uniquement des espaces."
    )]
    private ?string $firstname = null;

#[ORM\Column(length: 100, nullable: true)]
    private ?string $resetToken = null;
    
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
     
*A visual identifier that represents this user.*
*@see UserInterface*/
public function getUserIdentifier(): string{
    return (string) $this->email;}

    /** 
     
*@see UserInterface*
*@return list<string>*/
public function getRoles(): array{$roles = $this->roles;// guarantee every user at least has ROLE_USER$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /** 
     
*@param list<string> $roles*/
  public function setRoles(array $roles): static{$this->roles = $roles;

        return $this;
    }

    /** 
     
*@see PasswordAuthenticatedUserInterface*/
  public function getPassword(): string{
      return $this->password;}

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     
*@see UserInterface*/
  public function eraseCredentials(): void{
    // If you store any temporary, sensitive data on the user, clear it here// $this->plainPassword = null;
}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
    public function __toString()
    {
        return $this->getRoles();

    }
}