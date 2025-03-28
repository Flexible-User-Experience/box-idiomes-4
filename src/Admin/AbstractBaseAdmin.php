<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Enum\UserRolesEnum;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

abstract class AbstractBaseAdmin extends AbstractAdmin
{
    protected EntityManagerInterface $em;
    protected Security $ss;
    protected Environment $twig;
    protected FileService $fs;

    protected array $perPageOptions = [25, 50, 100, 200, 400];

    public function setEntityManager(EntityManagerInterface $em): self
    {
        $this->em = $em;

        return $this;
    }

    public function setSecurityHelper(Security $ss): self
    {
        $this->ss = $ss;

        return $this;
    }

    public function setTwigEnvironment(Environment $twig): self
    {
        $this->twig = $twig;

        return $this;
    }

    public function setFileService(FileService $fs): self
    {
        $this->fs = $fs;

        return $this;
    }

    public function getPerPageOptions(): array
    {
        return $this->perPageOptions;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::PER_PAGE] = 25;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'id';
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
        return ['csv', 'xls', 'xlsx'];
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

    protected function isAdminUser(): bool
    {
        return $this->isGranted(UserRolesEnum::ROLE_ADMIN);
    }
}
