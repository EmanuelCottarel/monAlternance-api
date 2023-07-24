<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Application\Read\ApplicationReadDto;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\SecurityBundle\Security;

class ApplicationStateProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security              $security,
        private readonly ApplicationRepository $applicationRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->security->getUser();
        $applications = $this->applicationRepository->findBy(["user" => $user]);

        $formattedApplication = [];
        foreach ($applications as $application) {
            $formattedApplication[] = new ApplicationReadDto(
                id         : $application->getId(),
                companyName: $application->getCompanyName(),
                submitedAt : $application->getSubmitedAt(),
                email      : $application->getEmail(),
                phoneNumber: $application->getPhoneNumber(),
                webSite    : $application->getWebSite(),
                status     : $application->getStatus()->getTitle()
            );
        }
        return $formattedApplication;
    }
}
