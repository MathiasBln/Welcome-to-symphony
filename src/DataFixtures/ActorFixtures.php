<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
      'Bruce Campbell',
      'Dana Delorenzo',
      'Ray Santiago',
      'Lucy Lawless',
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 25; $i++) {
            $actor = new Actor();
            $faker = Faker\Factory::create('us_US');
            $actor->setName($faker->name);
            $actor->addProgram($this->getReference('program_' . rand(0, 5)));
            $manager->persist($actor);
        }
        $manager->flush();

        $i = 0;
        foreach(self::ACTORS as $name ){
            $actor = new Actor();
            $actor->setName($name);
            $actor->addProgram($this->getReference('program_4'));
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
            $i++;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
