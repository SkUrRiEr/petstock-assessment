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

    /**
     * Given the authors REST endpoint
     * When given data to create a new author
     * Then the data returned will match the data given
     */
    public function testCreateAuthor()
    {
        $this->loadFixtures(array());

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'POST',
            '/authors',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name": "Alex Atona"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals("Alex Atona", $actual["name"]);
    }

    /**
     * Given the authors REST endpoint
     * When given invalid data to create a new author
     * Then the request will fail
     */
    public function testCreateAuthorFailure()
    {
        $this->loadFixtures(array());

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'POST',
            '/authors',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"invalid": "INVALID"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
    }
}
