<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Application\Write\UserProfileDataDto;
use Symfony\Bundle\SecurityBundle\Security;

class UserProfileDataProvider implements ProviderInterface
{
    public function __construct(
        private readonly Security $security,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserProfileDataDto
    {
        $user = $this->security->getUser();
        return new UserProfileDataDto(
            firstName: $user->getFirstName(),
            lastName : $user->getLastName(),
            email    : $user->getEmail()
        );
    }
}
