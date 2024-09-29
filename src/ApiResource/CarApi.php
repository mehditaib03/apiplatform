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
    paginationClientItemsPerPage: true,
    stateOptions: new Options(entityClass: Car::class),
)]

#[ApiFilter(SearchFilter::class, properties: [
    'year' => 'partial',
])]

class CarApi
{
    // #[ApiProperty(identifier: true)]
    public ?int $id = null;
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