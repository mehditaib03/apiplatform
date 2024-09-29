<?php
namespace App\ApiResource;

use App\Entity\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\State\EntityToDtoStateProvider;
use ApiPlatform\Doctrine\Orm\State\Options;
use App\State\EntityClassDtoStateProcessor;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'User',
    paginationItemsPerPage: 2,
    provider: EntityToDtoStateProvider::class,
    stateOptions: new Options(entityClass: User::class),
    description:'user data',
    paginationClientItemsPerPage: true,
    processor: EntityClassDtoStateProcessor::class,
    operations: [
        new Post(
            uriTemplate: '/mobile/user', 
            status: 301
        ),
        new Patch(uriTemplate: '/mobile/user/{id}', requirements: ['id' => '\d+']),
        new Put(uriTemplate: '/mobile/user/{id}', requirements: ['id' => '\d+']),
        new GetCollection(uriTemplate: '/mobile/user'),
        new Get(
            uriTemplate: '/mobile/user/{id}',
            requirements: ['id' => '\d+'] 
        ),
    ],
    normalizationContext: ['groups' => ['user:read', 'mobile:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]

#[ApiFilter(SearchFilter::class, properties: [
    'email' => 'partial',
])]

class UserApi
{
    #[Groups(['user:read'])]
    public ?int $id = null;
    #[Groups(['user:read', 'user:write'])]
    public ?string $email = null;
    #[Groups(['user:read', 'user:write'])]
    public ?string $fullname = null;
     /**
     * @var Collection<int, Car>
     */
    
    public ?array $cars = null;
}