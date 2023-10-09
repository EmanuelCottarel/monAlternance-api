<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Interaction\Read\InteractionReadDto;
use App\Entity\Interaction;
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

        $interactions = $this->interactionRepository->getInteractionsByApplicationFormat($application);
        $interactions[] = new InteractionReadDto(
            type: null,
            date: $application->getUpdatedAt()->format('d/m/Y'),
            title: $application->getStatus()->getTitle());

        return new JsonResponse($interactions);
    }

}
