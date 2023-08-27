<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly StatusRepository $statusRepository,
        private readonly UserRepository   $userRepository)
    {
        $this->faker = Faker\Factory::create("fr_FR");
        $this->faker->seed('FF54');
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadStatus($manager);
        $this->loadApplications($manager);
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
}
