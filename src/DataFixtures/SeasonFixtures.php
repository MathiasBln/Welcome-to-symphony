<?php


namespace App\DataFixtures;

use App\Entity\Season;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 66; $i++) {
            $season = new Season();
            $faker = Faker\Factory::create('us_US');
            $season->setYear($faker->year);
            $season->setDescription($faker->sentence);
            $season->setProgram($this->getReference('program_' . rand(0, 5)));
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
