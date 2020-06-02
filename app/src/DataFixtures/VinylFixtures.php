<?php
/**
 * Vinyl fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Vinyl;
use App\Entity\Category;
use App\Entity\Author;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class VinylFixtures.
 */
class VinylFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            50,
            'vinyls',
            function ($i) {
                $vinyl = new Vinyl();
                $vinyl->setTitle($this->faker->sentence);
                $vinyl->setYear($this->faker->year);
                $vinyl->setDescription($this->faker->sentence);
                $vinyl->setCategory($this->getRandomReference('categories'));
                $vinyl->setAuthor($this->getRandomReference('authors'));

                $tags = $this-> getRandomReferences(
                    'tags',
                    $this->faker->numberBetween(0, 5)
                );

                foreach($tags as $tag){
                    $vinyl->addTag($tag);
                }
                return $vinyl;
            }
        );

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }

    /**
     * @return array
     */
    public function getDependence(): array
    {
        return [AuthorFixtures::class];
    }
}
