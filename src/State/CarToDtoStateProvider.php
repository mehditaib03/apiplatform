<?php

namespace App\State;

use App\Entity\Car;
use App\ApiResource\CarApi;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CarToDtoStateProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
        $dtos = [];
        foreach ($entities as $entity) {
            $dtos[] = $this->mapEntityToDto($entity);
        }
        return $dtos;
    }
    private function mapEntityToDto(object $entity): object
    {
        if (!$entity instanceof Car) {
            throw new \InvalidArgumentException('Expected User entity');
        }

        $dto = new CarApi();
        $dto->id = $entity->getId();
        $dto->year = $entity->getYear();

        return $dto;
    }

}
