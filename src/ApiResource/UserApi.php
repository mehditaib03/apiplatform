<?php

namespace App\ApiResource;

use App\Entity\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\State\EntityToDtoStateProvider;
use ApiPlatform\Doctrine\Orm\State\Options;
use App\State\EntityClassDtoStateProcessor;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'User',
    paginationItemsPerPage: 2,
    provider: EntityToDtoStateProvider::class,
    stateOptions: new Options(entityClass: User::class),
    description: 'user data',
    routePrefix :'/mobile',
    paginationClientItemsPerPage: true,
    processor: EntityClassDtoStateProcessor::class,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            validationContext: ['groups' => ['Default', 'postValidation']],
        ),
        new Patch(),
        new Delete(),
    ],
)]

#[ApiFilter(SearchFilter::class, properties: [
    'email' => 'partial',
])]

class UserApi
{
    // #[Groups(['user:read'])]
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;
    // #[Groups(['user:read', 'user:write'])]
    #[Assert\Email]
    #[Assert\NotBlank]
    public ?string $email = null;
    // #[Groups(['user:read', 'user:write'])]
    #[Assert\NotBlank]
    public ?string $fullname = null;
    /**
     * @var Collection<int, Car>|[null]
     */
    public ?array $cars = null;
    // #[Groups(['user:write'])]

    #[ApiProperty(readable: false)]
    #[Assert\NotBlank(groups: ['postValidation'])]
    public ?string $password = null;
}
