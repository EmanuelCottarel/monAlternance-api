<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Application;
use App\Repository\ApplicationRepository;
use App\Repository\StatusRepository;
use Symfony\Bundle\SecurityBundle\Security;

class CreateApplicationProcessor implements ProcessorInterface
{

    public function __construct(public Security              $security,
                                public StatusRepository      $statusRepository,
                                public ApplicationRepository $applicationRepository)
    {

    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Application
    {
        if (isset($uriVariables['id'])) {
            $application = $this->applicationRepository->find($uriVariables['id']);
        } else {
            $application = new Application();
        }
        $application
            ->setCompanyName($data->companyName)
            ->setEmail($data->email)
            ->setSubmitedAt($data->submitedAt)
            ->setPhoneNumber(str_replace(" ","",$data->phoneNumber))
            ->setUser($this->security->getUser())
            ->setWebSite($data->webSite)
            ->setStatus($this->statusRepository->findOneBy(["title" => $data->status]));

        $this->applicationRepository->save($application, true);

        return $application;
    }

}
