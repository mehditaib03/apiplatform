<?php

namespace App\ApiResource;

use App\Entity\User;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\State\ResetPasswordProcessor;
use ApiPlatform\Metadata\GetCollection;
use App\State\EntityToDtoStateProvider;
use ApiPlatform\Doctrine\Orm\State\Options;
use App\State\EntityClassDtoStateProcessor;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    paginationItemsPerPage: 2,
    stateOptions: new Options(entityClass: User::class),
    description: 'user data',
    routePrefix: '/mobile',
    paginationClientItemsPerPage: true,
    processor: EntityClassDtoStateProcessor::class,
    provider: EntityToDtoStateProvider::class,
    operations: [
        // new Get(security: 'is_granted("ROLE_USER")'),
        new GetCollection(),
        new Get( requirements: ['id' => '\d+'] ),
        new Post(
            validationContext: ['groups' => ['Default', 'postValidation']],
        ),
        new Patch( requirements: ['id' => '\d+'] ),
        new Delete(),

    ],

    // normalizationContext: ['groups' => ['user:read', 'mobile:read']],
    // denormalizationContext: ['groups' => ['user:write']]
)]

#[ApiFilter(SearchFilter::class, properties: [
    'email' => 'partial',
])]

class UserApi
{
    // #[Groups(['user:read'])]
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\NotBlank(groups: ['resetValidation'])]
    public ?string $email = null;
    // #[Groups(['user:read', 'user:write'])]
    #[Assert\NotBlank]
    public ?string $fullname = null;

    /**
     * @var Collection<int, Car>|[null]
     */
    public ?array $cars = null;
    // #[Groups(['user:read'])]
    // #[Groups(['user:write'])]

    #[ApiProperty(readable: false)]
    #[Assert\NotBlank(groups: ['postValidation'])]
    public ?string $password = null;

    //     #[ApiProperty(readable: false)]
    //     #[Assert\NotBlank(groups: ['postValidation'])]
    //     public ?string $image = null;
}
