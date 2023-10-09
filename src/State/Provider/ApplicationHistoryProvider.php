<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ApplicationRepository;
use App\Repository\InteractionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApplicationHistoryProvider implements ProviderInterface
{
    public function __construct(
        private readonly ApplicationRepository $applicationRepository,
        private readonly InteractionRepository $interactionRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        $application = $this->applicationRepository->find($uriVariables['id']);
        return new JsonResponse($this->interactionRepository->getInteractionsByApplicationFormat($application));
    }

}
