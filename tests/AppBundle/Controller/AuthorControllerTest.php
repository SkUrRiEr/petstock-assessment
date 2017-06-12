<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    /**
     * Given the authors REST endpoint
     * When loaded without an ID
     * Then the data returned will match the expected data
     */
    public function testList()
    {
        $this->loadFixtures(array());

        $client = static::createClient();

        $client->request('GET', '/authors');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals(array(), $actual);
    }

    /**
     * Given the authors REST endpoint
     * When loaded with an ID which doesn't exist
     * Then the status code of the response will be 404
     */
    public function testMissing()
    {
        $this->loadFixtures(array());

        $client = static::createClient();

        $client->request('GET', '/authors/9900');

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Given the authors REST endpoint
     * When loaded without an ID while data is present
     * When the data returned will match the expected output
     */
    public function testPopulatedList()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData'
        ));

        $expected = include __DIR__ . "/expected/twoAuthors.php";

        $client = static::createClient();

        $client->request('GET', '/authors');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Given the authors REST endpoint
     * When loaded with an ID which exists
     * Then the data returned will match the expected output
     */
    public function testGetItem()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData'
        ));

        $expected = include __DIR__ . "/expected/frankMatthews.php";

        $client = static::createClient();

        $client->request('GET', '/authors/1');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $actual);
    }
}
