<?php

namespace App\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCalendarBlock extends AbstractBlockService
{
    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        $settings = $blockContext->getSettings();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
                'title' => 'Calendar',
            ),
            $response
        );
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'title' => 'Calendar',
            'content' => 'Default content',
            'template' => 'Admin/Block/calendar.html.twig',
        ));
    }
}
