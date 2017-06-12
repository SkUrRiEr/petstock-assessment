<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Author;

class TwoAuthorsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $author1 = new Author();
        $author1->setId(1);
        $author1->setName("Frank Matthews");
        $manager->persist($author1);

        $this->addReference("author-frank", $author1);

        $author2 = new Author();
        $author2->setId(2);
        $author2->setName("James Franklin");
        $manager->persist($author2);

        $this->addReference("author-james", $author2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
