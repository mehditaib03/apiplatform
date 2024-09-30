<?php

namespace App\State;

use App\Entity\Car;
use App\Entity\User;
use App\ApiResource\UserApi;
use ApiPlatform\Metadata\Post;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;


class EntityClassDtoStateProcessor implements ProcessorInterface
{
    public function __construct(
        private UserRepository $userRepository,
        #[Autowire(service: PersistProcessor::class)] private ProcessorInterface $persistProcessor,
        // using doctrine remove processor 
        #[Autowire(service: RemoveProcessor::class)] private ProcessorInterface $removeProcessor,
        private UserPasswordHasherInterface $userPasswordHasher,
        private CarRepository $carRepository,
        // data mapping 
        private MicroMapperInterface $microMapper
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assert($data instanceof UserApi);
        $entity = $this->mapDtoToEntity($data);
        // $entity = $this->mapDtoToEntity2($data);
        // remove user if we Detect Delete operation
        if ($operation instanceof DeleteOperationInterface) {
            $this->removeProcessor->process($entity, $operation, $uriVariables, $context);
            return null;
        }
        // reset password
        // if ($operation instanceof Post && $operation->getUriTemplate() === '/mobile/reset-password') {
        //     return $this->resetPassword($data);
        // }
        // continue 
        $this->persistProcessor->process($entity, $operation, $uriVariables, $context);
        $data->id = $entity->getId();
        return $data;
        // dd($entity);
    }

    private function mapDtoToEntity(object $dto): object
    {
        assert($dto instanceof UserApi);
        if ($dto->id) {
            $entity = $this->userRepository->find($dto->id);
            if (!$entity) {
                throw new \Exception(sprintf('Entity %d not found', $dto->id));
            }
        } else {
            $entity = new User();
        }
        $entity->setEmail($dto->email);
        $entity->setFullName($dto->fullname);
        if ($dto->password) {
            $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $dto->password));
        }
        // if ($dto->cars) {
        //     foreach ($dto->cars as $carId) {
        //         $car = $this->carRepository->find($carId);
        //         if ($car) {
        //             $entity->addCar($car); // Add or update the relationship
        //         }
        //     }
        // }
        // $entity->addCar($dto->cars);
        // TODO: handle dragon treasures
        return $entity;
    }

    private function mapDtoToEntity2(object $dto): object
    {
        return $this->microMapper->map($dto, User::class);
    }

    // private function resetPassword(UserApi $data)
    // {
    //     $user = $this->userRepository->findOneBy(['email' => $data->email]);

    //     if (!$user) {
    //         throw new \Exception('User not found.');
    //     }

    //     $user->setPassword($this->userPasswordHasher->hashPassword($user, $data->password));
    //     $this->userRepository->save($user);

    //     return ['message' => 'Password reset successfully.'];
    // }
}
