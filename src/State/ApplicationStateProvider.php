<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ApplicationStateProvider implements ProviderInterface
{

    public function __construct(
        private CollectionProvider    $itemProvider,
        private Security              $security)
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        $applications = $this->itemProvider->provide($operation, $uriVariables, $context);
        $results = array_values(array_filter(iterator_to_array($applications), fn($a) => $a->getUser() === $user));

        return $results;
    }
}
