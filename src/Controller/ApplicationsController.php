<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\User;
use App\Repository\ApplicationRepository;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ApplicationsController extends AbstractController
{
    public function __invoke($userId, ApplicationRepository $applicationRepository): array{

        return $applicationRepository->findBy(['user' => $userId]);
    }
}