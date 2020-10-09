<?php

namespace App\DataFixtures;

use App\Entity\Media;
Use Faker\Factory;
use App\Entity\Commune;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommunesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $codepostaux = [];
        for ($j = 0; $j < $faker->numberBetween(1 ,5); $j++){
            array_push($codepostaux, $faker->postcode);
        }

        for ($i = 0; $i < 30; $i++) {

            $commune = new Commune();
            $media = new Media();
            $commune->setNom($faker->name)
                ->setCode($faker->postcode)
                ->setCodeDepartement($faker->numberBetween(0 ,100))
                ->setCodeRegion($faker->numberBetween(0, 100))
                ->setCodesPostaux($codepostaux)
                ->setPopulation($faker->numberBetween(100, 1000000));

            $media->setCommune($commune)
                ->setVideo($faker->url)
                ->setImage($faker->url)
                ->setArticle($faker->url);

            $manager->persist($media);
            $manager->persist($commune);
            $manager->flush();
        }

    }
}