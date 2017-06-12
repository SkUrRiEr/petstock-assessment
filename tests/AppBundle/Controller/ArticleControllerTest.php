<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    /**
     * Given the articles REST endpoint
     * When loaded without an ID
     * Then the data returned will match the expected data
     */
    public function testList()
    {
        $this->loadFixtures(array());

        $client = static::createClient();

        $client->request('GET', '/articles');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals(array(), $actual);
    }

    /**
     * Given the articles REST endpoint
     * When loaded with an ID which doesn't exist
     * Then the status code of the response will be 404
     */
    public function testMissing()
    {
        $this->loadFixtures(array());

        $client = static::createClient();

        $client->request('GET', '/articles/9900');

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Given the articles REST endpoint
     * When loaded without an ID while data is present
     * When the data returned will match the expected output
     */
    public function testPopulatedList()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData',
            'AppBundle\DataFixtures\ORM\TwoArticlesData',
        ));

        $expected = include __DIR__ . "/expected/twoArticles.php";

        $client = static::createClient();

        $client->request('GET', '/articles');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Given the articles REST endpoint
     * When loaded with an ID which exists
     * Then the data returned will match the expected output
     */
    public function testGetItem()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData',
            'AppBundle\DataFixtures\ORM\TwoArticlesData',
        ));

        $expected = include __DIR__ . "/expected/firstArticle.php";

        $client = static::createClient();

        $client->request('GET', '/articles/1');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Given the articles REST endpoint
     * When given data to create a new article
     * Then the data returned will match the data given
     */
    public function testCreateArticle()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData'
        ));

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'POST',
            '/articles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"author_id":1,"title":"test post article","content":"Some test article"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals("Frank Matthews", $actual["author"]);
        $this->assertEquals("test post article", $actual["title"]);
        $this->assertEquals("Some test article", $actual["content"]);
    }

    /**
     * Given the articles REST endpoint
     * When given data with an unknown author to create a new article
     * Then the request will fail
     */
    public function testCreateArticleUnknownAuthor()
    {
        $this->loadFixtures(array());

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'POST',
            '/articles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"author_id":9999,"title":"test post article","content":"Some test article"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * Given the articles REST endpoint
     * When given invalid data to create a new article
     * Then the request will fail
     */
    public function testCreateArticleFailure()
    {
        $this->loadFixtures(array());

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'POST',
            '/articles',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"invalid": "INVALID"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * Given the articles REST endpoint
     * When given data to update an existing article
     * Then the data returned will match the expected data
     */
    public function testUpdateArticle()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData',
            'AppBundle\DataFixtures\ORM\TwoArticlesData',
        ));

        $expected = include __DIR__ . "/expected/updatedArticle.php";

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'PUT',
            '/articles/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"author_id":1,"title":"test updated post article","content":"Some updated test article"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        self::assertEquals($expected, $actual);
    }

    /**
     * Given the articles REST endpoint
     * When an attempt is made to update a nonexistent article
     * Then the request will fail
     */
    public function testUpdateNonexistentArticle()
    {
        $this->loadFixtures(array(
        ));

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'PUT',
            '/articles/9999',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"author_id":1,"title":"test updated post article","content":"Some updated test article"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Given the articles REST endpoint
     * When an attempt is made to update an article with invalid data
     * Then the request will fail
     */
    public function testUpdateArticleInvalid()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData',
            'AppBundle\DataFixtures\ORM\TwoArticlesData',
        ));

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'PUT',
            '/articles/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"invalid":"INVALID"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * Given the articles REST endpoint
     * When an attempt is made to update an article with a nonexistent author id
     * Then the request will fail
     */
    public function testUpdateArticleInvalidAuthor()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData',
            'AppBundle\DataFixtures\ORM\TwoArticlesData',
        ));

        $client = static::createClient();

        $client->followRedirects();

        $client->request(
            'PUT',
            '/articles/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"author_id":9999,"title":"test updated post article","content":"Some updated test article"}'
        );

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * Given the article REST endpoint
     * When asked to delete an article
     * Then the list of articles returned will not contain that article
     */
    public function testDeleteArticle()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\TwoAuthorsData',
            'AppBundle\DataFixtures\ORM\TwoArticlesData',
        ));

        $expected = include __DIR__ . "/expected/secondArticleList.php";

        $client = static::createClient();

        $client->followRedirects();

        $client->request('DELETE', '/articles/1');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $actual = json_decode($response->getContent(), true);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Given the articles REST endpoint
     * When asked to delete a nonexistent article
     * Then the request will fail
     */
    public function testDeleteNonExistentArticle()
    {
        $this->loadFixtures(array(
        ));

        $client = static::createClient();

        $client->request('DELETE', '/articles/1');

        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
    }
}
