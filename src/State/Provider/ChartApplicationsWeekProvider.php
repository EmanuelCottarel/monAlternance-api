<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ChartApplicationsWeekProvider implements ProviderInterface
{

       public function __construct(
        private readonly Security              $security,
        private readonly ApplicationRepository $applicationRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
       $user = $this->security->getUser();
       $applications = $this->applicationRepository->findBy(['user'=>$user]);

       $weekApp= [];
       for ($i = 0; $i<51; $i++){
           $weekApp[$i] = 'toto';
       }

       return $weekApp;
    }
}
