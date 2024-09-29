<?php

namespace App\State;

namespace App\State;

use App\Entity\User;
use App\ApiResource\CarApi;
use App\ApiResource\UserApi;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\State\Pagination\TraversablePaginator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class EntityToDtoStateProvider implements ProviderInterface
{
    public function __construct(
        #[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider,
        #[Autowire(service: ItemProvider::class)] private ProviderInterface $itemProvider,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
    // do this to inusre that the user is already exist
        if ($operation instanceof CollectionOperationInterface) {
            $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
            assert($entities instanceof Paginator);
            $dtos = [];
            foreach ($entities as $entity) {
                $dtos[] = $this->mapEntityToDto($entity);
            }
            return new TraversablePaginator(
                new \ArrayIterator($dtos),
                $entities->getCurrentPage(),
                $entities->getItemsPerPage(),
                $entities->getTotalItems()
            );
        }
        
        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);
        if (!$entity) {
            return null;
        }
        return $this->mapEntityToDto($entity);
    }

    private function mapEntityToDto(object $entity): object
    {
        if (!$entity instanceof User) {
            throw new \InvalidArgumentException('Expected User entity');
        }

        $dto = new UserApi();
        $dto->id = $entity->getId();
        $dto->email = $entity->getEmail();
        $dto->fullname = $entity->getFullname();

        // Map cars to CarApi DTOs
        $dto->cars = array_map(fn($car) => $this->mapCarToDto($car), $entity->getCars()->toArray());

        return $dto;
    }

    private function mapCarToDto(object $car): CarApi
    {
        return (new CarApi())
            ->setId($car->getId())
            ->setYear($car->getYear());
    }
}
