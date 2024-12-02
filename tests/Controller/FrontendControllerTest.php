<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontendControllerTest extends WebTestCase
{
    /**
     * Test HTTP request is successful
     *
     * @param string $url
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideSuccessfulUrls')]
    public function testPagesAreRedirected(string $url): void
    {
        $client = WebTestCase::createClient();
        $client->request('GET', '/');

        self::assertResponseRedirects();
    }

    /**
     * Test HTTP request is successful
     *
     * @param string $url
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideSuccessfulUrls')]
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
            array('/contacte-iframe'),
        );
    }

    /**
     * Test HTTP request is not found
     *
     * @param string $url
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideNotFoundUrls')]
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
            array('/serveis'),
            array('/academia'),
            array('/preinscripcions'),
            array('/politica-de-privacitat'),
            array('/credits'),
            array('/ca/pagina-trenacada'),
            array('/es/pagina-rota'),
            array('/en/broken-page'),
        );
    }
}
