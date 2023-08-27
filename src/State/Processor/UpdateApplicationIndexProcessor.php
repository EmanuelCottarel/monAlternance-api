<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class UpdateApplicationIndexProcessor implements ProcessorInterface
{
    public function __construct(private readonly ApplicationRepository $applicationRepository, private readonly Security $security)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $userId = $this->security->getUser()->getId();
        $movedApplication = $this->applicationRepository->findOneBy(['listIndex'=>$data->lastIndex, 'user'=>$userId]);
        $replacedApplication = $this->applicationRepository->findOneBy(['listIndex'=>$data->newIndex, 'user'=>$userId]);

        $movedApplication->setListIndex($data->newIndex);
        $replacedApplication->setListIndex($data->lastIndex);

        $this->applicationRepository->save($movedApplication, true);
        $this->applicationRepository->save($replacedApplication, true);
    }
}
