<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\Positive()]
    private ?int $number = null;

    #[ORM\Column(length: 125)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 125,
        maxMessage: "Votre nom de rue est trop long. Il ne doit pas dépasser 125 caractères."
    )]
    #[Assert\Regex(
        pattern: "/\S+/",
        message: "Le champ ne peut pas contenir uniquement des espaces."
    )]
    private ?string $street = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 100,
        maxMessage: "Votre nom de Ville est trop long. Il ne doit pas dépasser 100 caractères."
    )]
    #[Assert\Regex(
        pattern: "/\S+/",
        message: "Le champ ne peut pas contenir uniquement des espaces."
    )]
    private ?string $city = null;

    #[ORM\Column(length: 125)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 125,
        maxMessage: "Votre nom de département est trop long. Il ne doit pas dépasser 125 caractères."
    )]
    #[Assert\Regex(
        pattern: "/\S+/",
        message: "Le champ ne peut pas contenir uniquement des espaces."
    )]
    private ?string $county = null;

    #[ORM\Column(length: 125)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 125,
        maxMessage: "Votre nom de pays est trop long. Il ne doit pas dépasser 125 caractères."
    )]
    #[Assert\Regex(
        pattern: "/\S+/",
        message: "Le champ ne peut pas contenir uniquement des espaces."
    )]
    private ?string $country = null;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'adresse')]
    private Collection $clients;

    /**
     * @var Collection<int, Operation>
     */
    #[ORM\OneToMany(targetEntity: Operation::class, mappedBy: 'adresse')]
    private Collection $operations;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->operations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function setCounty(string $county): static
    {
        $this->county = $county;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setAdresse($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getAdresse() === $this) {
                $client->setAdresse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Operation>
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): static
    {
        if (!$this->operations->contains($operation)) {
            $this->operations->add($operation);
            $operation->setAdresse($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): static
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getAdresse() === $this) {
                $operation->setAdresse(null);
            }
        }

        return $this;
    }
}
