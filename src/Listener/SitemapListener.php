<?php

namespace App\Listener;

use DateTimeImmutable;
use DateTimeInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapListener implements EventSubscriberInterface
{
    private UrlGeneratorInterface $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SitemapPopulateEvent::class => 'populate',
        ];
    }

    public function populate(SitemapPopulateEvent $event): void
    {
        $section = $event->getSection();
        if (is_null($section) || 'default' === $section) {
            // Homepage
            $url = $this->makeUrl('app_homepage');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url), 'default');
            // Services view
            $url = $this->makeUrl('app_services');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 1), 'default');
            // About us view
            $url = $this->makeUrl('app_academy');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 1), 'default');
            // Contact view
            $url = $this->makeUrl('app_contact');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 0.5), 'default');
            // Privacy Policy view
            $url = $this->makeUrl('app_privacy_policy');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 0.1), 'default');
            // Credits view
            $url = $this->makeUrl('app_credits');
            $event
                ->getUrlContainer()
                ->addUrl($this->makeUrlConcrete($url, 0.1), 'default');
        }
    }

    private function makeUrl(string $routeName): string
    {
        return $this->router->generate(
            $routeName, [], UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function makeUrlConcrete(string $url, int $priority = 1, ?DateTimeInterface $date = null): UrlConcrete
    {
        return new UrlConcrete(
            $url,
            $date ?? new DateTimeImmutable(),
            UrlConcrete::CHANGEFREQ_WEEKLY,
            $priority
        );
    }
}
