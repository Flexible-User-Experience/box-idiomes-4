<?php

namespace App\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

abstract class AbstractBaseAdmin extends AbstractAdmin
{
    protected EntityManagerInterface $em;
    protected Security $ss;
    protected Environment $twig;
    protected UploaderHelper $vus;
    protected CacheManager $lis;

    protected array $perPageOptions = [25, 50, 100, 200, 400];
    protected int $maxPerPage = 25;

    public function __construct($code, $class, $baseControllerName, EntityManagerInterface $em, Security $ss, Environment $twig, UploaderHelper $vus, CacheManager $lis)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->em = $em;
        $this->ss = $ss;
        $this->twig = $twig;
        $this->vus = $vus;
        $this->lis = $lis;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection
            ->remove('show')
            ->remove('batch')
        ;
    }

    public function configureBatchActions(array $actions): array
    {
        unset($actions['delete']);

        return $actions;
    }

    public function getExportFormats(): array
    {
        return ['csv', 'xls'];
    }

    protected function checkUserHasRole(string $role): bool
    {
        try {
            return $this->ss->isGranted($role);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            return false;
        }
    }

    protected function getDefaultFormBoxArray(string $label, string $bootstrapGrid = 'md', string $bootstrapSize = '6', string $boxClass = 'primary'): array
    {
        return [
            'label' => $label,
            'class' => 'col-'.$bootstrapGrid.'-'.$bootstrapSize,
            'box_class' => 'box box-'.$boxClass,
        ];
    }

    protected function getFormMdSuccessBoxArray(string $label, int $bootstrapColSize = 6): array
    {
        return $this->getDefaultFormBoxArray($label, 'md', (string) $bootstrapColSize, 'success');
    }

    protected function getShowMdInfoBoxArray(string $label, int $bootstrapColSize = 6, string $boxClass = 'info'): array
    {
        return [
            'label' => $label,
            'class' => 'col-md-'.$bootstrapColSize,
            'box_class' => 'box box-'.$boxClass,
        ];
    }

    protected function isFormToCreateNewRecord(): bool
    {
        return !$this->id($this->getSubject());
    }

    protected function isChildForm(): bool
    {
        return $this->hasParentFieldDescription();
    }

    protected function getImageHelperFormMapperWithThumbnail(): string
    {
        return ($this->getSubject() ? $this->getSubject()->getImageName() ? '<img src="'.$this->lis->getBrowserPath(
                    $this->vus->asset($this->getSubject(), 'imageFile'),
                    '480xY'
                ).'" class="admin-preview img-responsive" alt="thumbnail"/>' : '' : '').'<span style="width:100%;display:block;">amplada mínima 1200px (màx. 10MB amb JPG o PNG)</span>';
    }

    /**
     * Get image helper form mapper with thumbnail for black&white.
     */
    protected function getImageHelperFormMapperWithThumbnailBW(): string
    {
        return ($this->getSubject() ? $this->getSubject()->getImageNameBW() ? '<img src="'.$this->lis->getBrowserPath(
                    $this->vus->asset($this->getSubject(), 'imageFileBW'),
                    '480xY'
                ).'" class="admin-preview img-responsive" alt="thumbnail"/>' : '' : '').'<span style="width:100%;display:block;">amplada mínima 1200px (màx. 10MB amb JPG o PNG)</span>';
    }

    protected function getImageHelperFormMapperWithThumbnailGif(): string
    {
        return ($this->getSubject() ? $this->getSubject()->getGifName() ? '<img src="'.$this->lis->getBrowserPath(
                    $this->vus->asset($this->getSubject(), 'gifFile'),
                    '480xY'
                ).'" class="admin-preview img-responsive" alt="thumbnail"/>' : '' : '').'<span style="width:100%;display:block;">mida 780x1168px (màx. 10MB amb GIF)</span>';
    }

    protected function getImageHelperFormMapperWithThumbnailAspectRatio(): string
    {
        return ($this->getSubject() ? $this->getSubject()->getImageName() ? '<img src="'.$this->lis->getBrowserPath(
                    $this->vus->asset($this->getSubject(), 'imageFile'),
                    '480xY'
                ).'" class="admin-preview img-responsive" alt="thumbnail"/>' : '' : '').'<span style="width:100%;display:block;">Les imatges han de ser verticals i amplada mínima 600px (màx. 10MB)</span>';
    }

    /**
     * Get image helper form mapper with thumbnail.
     *
     * @param string $attribute
     * @param string $uploaderMapping
     *
     * @throws \Twig\Error\Error
     */
    protected function getSmartHelper($attribute, $uploaderMapping): string
    {
        $fs = $this->getConfigurationPool()->getContainer()->get('app.file_service');

        if ($this->getSubject() && $this->getSubject()->$attribute()) {
            if ($fs->isPdf($this->getSubject(), $uploaderMapping)) {
                // PDF case
                return $this->twig->render('Admin/Helpers/pdf.html.twig', [
                    'attribute' => $this->getSubject()->$attribute(),
                    'subject' => $this->getSubject(),
                    'uploaderMapping' => $uploaderMapping,
                ]);
            } else {
                // Image case
                return $this->twig->render('Admin/Helpers/image.html.twig', [
                    'attribute' => $this->getSubject()->$attribute(),
                    'subject' => $this->getSubject(),
                    'uploaderMapping' => $uploaderMapping,
                ]);
            }
        } else {
            // Undefined case
            return '<span style="width:100%;display:block;">Pots adjuntar un PDF o una imatge. Pes màxim 10MB.</span>';
        }
    }
}
