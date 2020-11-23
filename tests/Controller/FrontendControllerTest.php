<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontendControllerTest extends WebTestCase
{
    /**
     * Test HTTP request is successful
     *
     * @dataProvider provideSuccessfulUrls
     * @param string $url
     */
    public function testPagesAreSuccessful(string $url): void
    {
        $client = WebTestCase::createClient();
        $client->request('GET', $url);

        self::assertResponseIsSuccessful();
    }

    /**
     * Successful Urls provider
     *
     * @return array
     */
    public function provideSuccessfulUrls(): array
    {
        return array(
            array('/'),
            array('/serveis'),
            array('/academia'),
            array('/contacte'),
            array('/preinscripcions'),
            array('/politica-de-privacitat'),
            array('/credits'),
            array('/sitemap/sitemap.default.xml'),
        );
    }

    /**
     * Test HTTP request is not found
     *
     * @dataProvider provideNotFoundUrls
     * @param string $url
     */
    public function testPagesAreNotFound(string $url): void
    {
        $client = WebTestCase::createClient();
        $client->request('GET', $url);

        self::assertResponseStatusCodeSame(404);
    }

    /**
     * Not found Urls provider
     *
     * @return array
     */
    public function provideNotFoundUrls(): array
    {
        return array(
            array('/ca/pagina-trenacada'),
            array('/es/pagina-rota'),
            array('/en/broken-page'),
        );
    }
}
