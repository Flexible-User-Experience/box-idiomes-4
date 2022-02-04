<?php

namespace App\Block;

use App\Service\ChartsFactoryService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class ChartsBlock extends AbstractBlockService
{
    private ChartsFactoryService $cfs;

    public function __construct(Environment $templating, ChartsFactoryService $cfs)
    {
        parent::__construct($templating);
        $this->cfs = $cfs;
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        $settings = $blockContext->getSettings();

        return $this->renderResponse(
            $blockContext->getTemplate(),
            [
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
                'title' => 'Charts',
                'chart' => $this->cfs->buildLastYearResultsChart(),
            ],
            $response
        );
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => 'Charts',
            'content' => 'Default content',
            'template' => 'Admin/Block/charts.html.twig',
        ]);
    }
}
