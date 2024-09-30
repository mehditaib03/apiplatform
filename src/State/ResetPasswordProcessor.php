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
use App\ApiResource\ResetPasswordApi;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfonycasts\MicroMapper\MicroMapperInterface;


class ResetPasswordProcessor implements ProcessorInterface
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
        return null;
        // return [
        //     'code' => 200, // HTTP success code
        //     'message' => 'Password reset email sent successfully.',
        // ];

        assert($data instanceof ResetPasswordApi);
        $entity = $this->mapDtoToEntity($data);
        // $this->persistProcessor->process($entity, $operation, $uriVariables, $context);
        $data->id = $entity->getId();
        return $data;
    }

    private function mapDtoToEntity(object $dto): object
    {
        assert($dto instanceof ResetPasswordApi);
        $entity = new User();
        return $entity;
    }
}
