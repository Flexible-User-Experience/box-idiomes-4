<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FrontendControllerTest extends WebTestCase
{
    public function testPagesAreRedirected(): void
    {
        $client = WebTestCase::createClient();
        $client->request('GET', '/');
        self::assertResponseRedirects();
    }
    
    #[DataProvider('provideSuccessfulUrls')]
    public function testPagesAreSuccessful(string $url): void
    {
        $client = WebTestCase::createClient();
        $client->request('GET', $url);
        self::assertResponseIsSuccessful();
    }
    
    public static function provideSuccessfulUrls(): array
    {
        return [
            ['/contacte-iframe'],
        ];
    }

    #[DataProvider('provideNotFoundUrls')]
    public function testPagesAreNotFound(string $url): void
    {
        $client = WebTestCase::createClient();
        $client->request('GET', $url);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
    
    public static function provideNotFoundUrls(): array
    {
        return [
           ['/serveis'],
           ['/academia'],
           ['/preinscripcions'],
           ['/politica-de-privacitat'],
           ['/credits'],
           ['/ca/pagina-trenacada'],
           ['/es/pagina-rota'],
           ['/en/broken-page'],
        ];
    }
}
