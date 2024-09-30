<?php

namespace App\ApiResource;

use App\Entity\Car;
// use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\ApiFilter;
use App\State\CarToDtoStateProvider;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'Car',
    provider: CarToDtoStateProvider::class,
    paginationItemsPerPage: 2,
    routePrefix: '/mobile',
    paginationClientItemsPerPage: true,
    stateOptions: new Options(entityClass: Car::class),
    // normalizationContext: ['groups' => ['user:read', 'mobile:read']],
    // denormalizationContext: ['groups' => ['user:write']]
)]

#[ApiFilter(SearchFilter::class, properties: [
    'year' => 'partial',
])]

// #[Groups(['user:read'])]
class CarApi
{

    #[ApiProperty(identifier: true, readable: true)]
    public ?int $id = null;

    #[ApiProperty(readable: true)]
    public ?int $year = null;


    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }
}
