<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Application;
use App\Entity\Interaction;
use App\Enums\InteractionTypes;
use App\Repository\ApplicationRepository;
use App\Repository\InteractionTypeRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CreateApplicationProcessor implements ProcessorInterface
{

    public function __construct(public Security                  $security,
                                public StatusRepository          $statusRepository,
                                public ApplicationRepository     $applicationRepository,
                                public InteractionTypeRepository $interactionTypeRepository,
                                public EntityManagerInterface    $manager)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Application
    {
        $user = $this->security->getUser();


        $application = new Application();

        $application
            ->setCompanyName($data->companyName)
            ->setEmail($data->email)
            ->setSubmitedAt($data->submitedAt)
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setPhoneNumber(str_replace(" ", "", $data->phoneNumber))
            ->setUser($user)
            ->setWebSite($data->webSite)
            ->setStatus($this->statusRepository->findOneBy(["title" => $data->status]))
            ->setListIndex(count($this->applicationRepository->findBy(["user" => $user])) + 1);
        $this->manager->persist($application);

        $interaction = new Interaction();
        $interaction->setApplication($application)
            ->setType($this->interactionTypeRepository->findOneBy(["title" => InteractionTypes::EMAIL->value]))
            ->setTitle("Premier contact")
            ->setDate($data->submitedAt);

        $this->manager->persist($interaction);
        $this->manager->flush();

        return $application;
    }

}
