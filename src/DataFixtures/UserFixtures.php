<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();


        for ($i = 0; $i < 10; $i++){

            $user = new User();

            $password = $faker->password;
            $user->setEmail($faker->email)
                ->setPassword($this->passwordEncoder->encodePassword($user, $password))
                ->setRoles(["ROLE_USER"]);

            $manager->persist($user);
        }
        $manager->flush();
    }
}