<?php

namespace App\ApiResource;

use App\Entity\Car;
// use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use App\Entity\User;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiFilter;
use App\State\CarToDtoStateProvider;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\State\ResetPasswordProcessor;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Reset-Password',

    routePrefix: '/mobile',
    paginationClientItemsPerPage: true,

    processor: ResetPasswordProcessor::class,
    operations: [ new Post()]

)]

// #[Groups(['user:read'])]
class ResetPasswordApi
{

    #[ApiProperty(identifier: true, readable: true, writable:false)]
    public ?int $id = null;

    #[ApiProperty(readable: true, writable:true)]
    public ?string $email = null;

    #[ApiProperty(readable: true, writable:false)]
    public ?string $fullname = null;

}
