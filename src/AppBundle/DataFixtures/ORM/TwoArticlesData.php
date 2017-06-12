<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Article;

class TwoArticlesData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $article1 = new Article();
        $article1->setId(1);
        $article1->setAuthor($this->getReference("author-frank"));
        $article1->setTitle("Test Article 1");
        $article1->setContent("Content for test article 1");
        $article1->setCreatedAt(new \DateTime("2016-01-01 00:00:00"));
        $article1->setUpdatedAt(new \DateTime("2016-01-02 01:01:01"));
        $manager->persist($article1);

        $article2 = new Article();
        $article2->setId(2);
        $article2->setAuthor($this->getReference("author-james"));
        $article2->setTitle("Test Article 2");
        $article2->setContent("Content for test article 2");
        $article2->setCreatedAt(new \DateTime("2016-02-02 00:00:00"));
        $article2->setUpdatedAt(new \DateTime("2016-02-03 02:02:02"));
        $manager->persist($article2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
