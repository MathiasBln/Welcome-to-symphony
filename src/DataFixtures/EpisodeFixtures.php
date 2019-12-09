<?php


namespace App\DataFixtures;


use App\Entity\Episode;
use App\Service\Slugify;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 666; $i++) {
            $episode = new Episode();
            $slugify = new Slugify();
            $faker = Faker\Factory::create('us_US');
            $episode->setTitle($faker->sentence);
            $episode->setNumber($faker->randomDigit);
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . rand(1, 66)));
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $manager->persist($episode);
            $this->addReference('episode_' . $i, $episode);
        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }

}
