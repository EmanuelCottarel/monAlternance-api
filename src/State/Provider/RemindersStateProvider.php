<?php

namespace App\State\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RemindersStateProvider implements ProviderInterface
{
    public function __construct(
        private readonly CollectionProvider $collectionProvider,
        private readonly Security           $security
    )
    {
    }

    private int $timeToReminder = 7;

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        $applications = $this->collectionProvider->provide($operation, $uriVariables, $context);
        $results = array_values(array_filter(iterator_to_array($applications), fn($a) => $a->getUser() === $user));
        $categories=[];
        foreach ($results as $app) {
            $timeInterval = new \DateInterval('P'.$this->timeToReminder.'D');
            $reminderDate = date_add(\DateTime::createFromImmutable($app->getSubmitedAt()), $timeInterval);
            $categories[$reminderDate->format('d/m/Y')][] = $app;
        }
        return $categories;
    }
}
