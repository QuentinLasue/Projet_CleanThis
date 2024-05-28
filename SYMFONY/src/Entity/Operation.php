<?php

namespace App\Entity;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\TypeOperation;
use App\Entity\User;
use App\Repository\OperationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Regex(
        pattern: "/\S+/",
        message: "La description ne peut pas contenir uniquement des espaces."
    )]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan("today")]
    private ?\DateTimeInterface $dateForecast = null;



    #[ORM\ManyToOne(inversedBy: 'operations')] 


    private ?Adresse $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'operations')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'operations')]
    private ?TypeOperation $type = null;

    #[ORM\ManyToOne(inversedBy: 'operations')]

 

    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $namePhoto = null;

    #[ORM\Column(nullable: true)]
    private ?float $customPrix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getDateForecast(): ?\DateTimeInterface
    {
        return $this->dateForecast;
    }

    public function setDateForecast(\DateTimeInterface $dateForecast): static
    {
        $this->dateForecast = $dateForecast;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getType(): ?TypeOperation
    {
        return $this->type;
    }

    public function setType(?TypeOperation $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getNamePhoto(): ?string
    {
        return $this->namePhoto;
    }

    public function setNamePhoto(?string $namePhoto): static
    {
        $this->namePhoto = $namePhoto;

        return $this;
    }

    public function getCustomPrix(): ?float
    {
        return $this->customPrix;
    }

    public function setCustomPrix(?float $customPrix): static
    {
        $this->customPrix = $customPrix;

        return $this;
    }
}
