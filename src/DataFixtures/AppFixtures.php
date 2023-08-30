<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Interaction;
use App\Entity\InteractionType;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\ApplicationRepository;
use App\Repository\InteractionTypeRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly StatusRepository $statusRepository,
        private readonly UserRepository   $userRepository,
        private readonly ApplicationRepository $applicationRepository,
        private readonly InteractionTypeRepository $interactionTypeRepository)
    {
        $this->faker = Faker\Factory::create("fr_FR");
        $this->faker->seed('FF54');
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadStatus($manager);
        $this->loadApplications($manager);
        $this->loadInteractionType($manager);
        $this->loadInteractions($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setRoles(["ROLE_USER, ROLE_ADMIN"])
            ->setEmail("admin@gmail.com")
            ->setFirstName('Admin')
            ->setLastName("Admin")
            ->setPassword("$2y$13$8LWHylH978vGaXTKXTzS.efTkoZVSYUZchsCBsVRg7JbDzIDhdnQ6");

        $manager->persist($admin);

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setEmail(mb_strtolower($user->getFirstName() . $user->getLastName() . "@gmail.com"))
                ->setPassword("$2y$13$8LWHylH978vGaXTKXTzS.efTkoZVSYUZchsCBsVRg7JbDzIDhdnQ6")
                ->setRoles(["ROLE_USER"]);

            $manager->persist($user);
        }

        $manager->flush();
        $manager->clear();

    }

    private function loadStatus(ObjectManager $manager)
    {
        $waiting = new Status();
        $waiting->setTitle("En attente");
        $manager->persist($waiting);

        $accepted = new Status();
        $accepted->setTitle("Acceptée");
        $manager->persist($accepted);

        $refused = new Status();
        $refused->setTitle("Refusée");
        $manager->persist($refused);

        $manager->flush();
        $manager->clear();
    }

    private function loadApplications(ObjectManager $manager)
    {
        foreach ($this->userRepository->findAll() as $user)
            for ($i = 0; $i < 10; $i++) {
                $application = new Application();
                $application->setCompanyName($this->faker->company())
                    ->setEmail($this->faker->email())
                    ->setWebSite("www" . $application->getCompanyName() . ".com")
                    ->setPhoneNumber($this->faker->phoneNumber())
                    ->setSubmitedAt(new \DateTimeImmutable())
                    ->setStatus($this->faker->randomElement($this->statusRepository->findAll()))
                    ->setUser($user)
                    ->setListIndex($i);

                $manager->persist($application);
            }

        $manager->flush();
        $manager->clear();

    }

    private function loadInteractionType(ObjectManager $manager)
    {
        $mail = new InteractionType();
        $mail->setTitle("Email");
        $manager->persist($mail);

        $letter = new InteractionType();
        $letter->setTitle("Courrier");
        $manager->persist($letter);

        $interview = new InteractionType();
        $interview->setTitle("Entretien physique");
        $manager->persist($interview);

        $phoneInterview = new InteractionType();
        $phoneInterview->setTitle("Entretien téléphonique");
        $manager->persist($phoneInterview);

        $manager->flush();
    }

    private function loadInteractions(ObjectManager $manager)
    {
        $applications = $this->applicationRepository->findAll();
        foreach ($applications as $application) {
            for ($i = rand(1, 5); $i > 0; $i--) {

                if ($i > 0) {
                    $int1 = new Interaction();
                    $int1->setType($this->interactionTypeRepository->find(1));
                    $int1->setDate(new \DateTimeImmutable());
                    $int1->setTitle("Email au rh");
                    $int1->setApplication($application);
                    $manager->persist($int1);
                }

                if ($i > 1) {
                    $int2 = new Interaction();
                    $int2->setType($this->interactionTypeRepository->find(4));
                    $int2->setDate(new \DateTimeImmutable());
                    $int2->setTitle("Entretien téléphonique avec les Rh");
                    $int2->setApplication($application);
                    $manager->persist($int2);
                }

                if ($i > 2) {
                    $int3 = new Interaction();
                    $int3->setType($this->interactionTypeRepository->find(3));
                    $int3->setDate(new \DateTimeImmutable());
                    $int3->setTitle("Entretien physique avec les Rh");
                    $int3->setApplication($application);
                    $manager->persist($int3);
                }
                if ($i > 3) {
                    $int4 = new Interaction();
                    $int4->setType($this->interactionTypeRepository->find(4));
                    $int4->setDate(new \DateTimeImmutable());
                    $int4->setTitle("Entretien physique avec les Rh");
                    $int4->setApplication($application);
                    $manager->persist($int4);
                }
                if ($i > 4) {
                    $int5 = new Interaction();
                    $int5->setType($this->interactionTypeRepository->find(4));
                    $int5->setDate(new \DateTimeImmutable());
                    $int5->setTitle("Entretien physique avec le chef d'équipe");
                    $int5->setApplication($application);
                    $manager->persist($int5);
                }

            }
            $manager->persist($application);
            $manager->flush();
        }
    }
}
