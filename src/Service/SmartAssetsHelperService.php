<?php

namespace App\Service;

use App\Entity\Teacher;
use App\Kernel;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final class SmartAssetsHelperService
{
    public const HTTP_PROTOCOL = 'https://';

    private UploaderHelper $uh;
    private CacheManager $icm;
    private KernelInterface $kernel;
    private string $pub;
    private string $amd;
    private string $bba;
    private string $bpn;

    public function __construct(UploaderHelper $uh, CacheManager $icm, KernelInterface $kernel, ParameterBagInterface $pb)
    {
        $this->uh = $uh;
        $this->icm = $icm;
        $this->kernel = $kernel;
        $this->pub = $pb->get('project_url_base');
        $this->amd = $pb->get('mailer_destination');
        $this->bba = $pb->get('boss_address');
        $this->bpn = $pb->get('boss_phone_number_1');
    }

    public function getAmd(): string
    {
        return $this->amd;
    }

    public function getBba(): string
    {
        return $this->bba;
    }

    public function getBpn(): string
    {
        return $this->bpn;
    }

    /**
     * Determine if this PHP script is executed under a CLI context.
     */
    public function isCliContext(): string
    {
        return Kernel::CLI_API === PHP_SAPI;
    }

    public function getTeacherImageAssetPath(Teacher $teacher, string $filter): string
    {
        return $this->icm->getBrowserPath($this->uh->asset($teacher, 'imageFile'), $filter);
    }

    /**
     * Always return absolute URL path, even in CLI contexts.
     */
    public function getAbsoluteAssetPathContextIndependent($assetPath): string
    {
        $package = new UrlPackage(self::HTTP_PROTOCOL.$this->pub.'/', new EmptyVersionStrategy());

        return $package->getUrl($assetPath);
    }

    /**
     * If is CLI context returns absolute file path, otherwise returns absolute URL path.
     */
    public function getAbsoluteAssetPathByContext($assetPath): string
    {
        $result = $this->getAbsoluteAssetPathContextIndependent($assetPath);
        if ($this->isCliContext()) {
            $result = $this->kernel->getProjectDir().DIRECTORY_SEPARATOR.'public'.$assetPath;
        }

        return $result;
    }

    /**
     * Always return relative URL path, even in CLI contexts.
     */
    public function getRelativeAssetPathContextIndependent($assetPath): string
    {
        return (new UrlPackage('/', new EmptyVersionStrategy()))->getUrl($assetPath);
    }

    /**
     * If is CLI context returns absolute file path, otherwise returns relative URL path.
     */
    public function getRelativeAssetPathByContext($assetPath): string
    {
        $result = $this->getRelativeAssetPathContextIndependent($assetPath);
        if ($this->isCliContext()) {
            $result = $this->kernel->getProjectDir().DIRECTORY_SEPARATOR.'public'.$assetPath;
        }

        return $result;
    }

    /**
     * Returns absolute file path.
     */
    public function getAbsoluteAssetFilePath($assetPath): string
    {
        return $this->kernel->getProjectDir().DIRECTORY_SEPARATOR.'public'.$assetPath;
    }
}
