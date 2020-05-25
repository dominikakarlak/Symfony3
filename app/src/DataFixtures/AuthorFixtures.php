<?php
/**
 * Author fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Vinyl;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AuthorFixtures.
 */
class AuthorFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'authors', function ($i) {
            $author = new Author();
            $author->setName($this->faker->sentence);



            return $author;
        });

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
        return [VinylFixtures::class];
    }
}
