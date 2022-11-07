<?php

namespace App\Block;

use App\Form\Model\FilterCalendarEventModel;
use App\Form\Type\FilterCalendarEventsType;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class EventCalendarBlock extends AbstractBlockService
{
    private FormFactoryInterface $ff;

    public function __construct(Environment $templating, FormFactoryInterface $ff)
    {
        parent::__construct($templating);
        $this->ff = $ff;
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        $settings = $blockContext->getSettings();
        $filterCalendarEventsForm = $this->ff->create(FilterCalendarEventsType::class, new FilterCalendarEventModel());

        return $this->renderResponse(
            $blockContext->getTemplate(),
            [
                'block' => $blockContext->getBlock(),
                'settings' => $settings,
                'title' => 'Calendar',
                'filter' => $filterCalendarEventsForm->createView(),
            ],
            $response
        );
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => 'Calendar',
            'content' => 'Default content',
            'template' => 'Admin/Block/calendar.html.twig',
        ]);
    }
}
