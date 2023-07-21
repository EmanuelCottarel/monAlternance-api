<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{

    public function __construct()
    {
        $this->faker = Faker\Factory::create("fr_FR");
        $this->faker->seed('FF54');
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager){
        $admin = new User();
        $admin->setRoles(["ROLE_USER, ROLE_ADMIN"])
            ->setEmail("admin@gmail.com")
            ->setFirstName('Admin')
            ->setLastName("Admin")
            ->setPassword("$2y$13$8LWHylH978vGaXTKXTzS.efTkoZVSYUZchsCBsVRg7JbDzIDhdnQ6");

        $manager->persist($admin);

    for ($i = 0; $i < 5; $i ++){
        $user = new User();
        $user->setFirstName($this->faker->firstName())
            ->setLastName($this->faker->lastName())
            ->setEmail($user->getFirstName().$user->getLastName()."@gmail.com")
            ->setPassword("$2y$13$8LWHylH978vGaXTKXTzS.efTkoZVSYUZchsCBsVRg7JbDzIDhdnQ6")
            ->setRoles("ROLE_USER");

        $manager->persist($user);
    }

        $manager->flush();
        $manager->clear();

    }
}
