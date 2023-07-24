<?php
namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserHashPasswordProcessor implements ProcessorInterface
{

    public function __construct(private readonly ProcessorInterface $processor, private readonly UserPasswordHasherInterface $passwordHasher){

    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data->getPlainPassword()){
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }
        $hashedPassword= $this->passwordHasher->hashPassword(
            $data,
            $data->getPlainPassword()

        );
        $data->setPassword($hashedPassword);
        $data->eraseCredentials();

        $data->setRoles(['ROLE_USER']);

        return $this->processor->process($data, $operation, $uriVariables, $context);


    }
}