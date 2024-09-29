<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
#[ORM\Entity(repositoryClass: CarRepository::class)]

class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $year = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    private ?User $usercar = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'car')]
    private Collection $caruser;

    public function __construct()
    {
        $this->caruser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getUsercar(): ?User
    {
        return $this->usercar;
    }

    public function setUsercar(?User $usercar): static
    {
        $this->usercar = $usercar;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCaruser(): Collection
    {
        return $this->caruser;
    }

    public function addCaruser(user $caruser): static
    {
        if (!$this->caruser->contains($caruser)) {
            $this->caruser->add($caruser);
            $caruser->setCar($this);
        }

        return $this;
    }

    public function removeCaruser(User $caruser): static
    {
        if ($this->caruser->removeElement($caruser)) {
            // set the owning side to null (unless already changed)
            if ($caruser->getCar() === $this) {
                $caruser->setCar(null);
            }
        }

        return $this;
    }
}
