<?php

namespace App\Service;

use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpKernel\KernelInterface;

class SmartAssetsHelperService
{
    const HTTP_PROTOCOL = 'https://';
    const PHP_SERVER_API_CLI_CONTEXT = 'cli';

    private KernelInterface $kernel;
    private string $pub;
    private string $amd;
    private string $bba;
    private string $bpn;

    public function __construct(KernelInterface $kernel, string $pub, string $amd, string $bba, string $bpn)
    {
        $this->kernel = $kernel;
        $this->pub = $pub;
        $this->amd = $amd;
        $this->bba = $bba;
        $this->bpn = $bpn;
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
    public function isCliContext()
    {
        return self::PHP_SERVER_API_CLI_CONTEXT === php_sapi_name();
    }

    /**
     * Always return absolute URL path, even in CLI contexts.
     */
    public function getAbsoluteAssetPathContextIndependent($assetPath)
    {
        $package = new UrlPackage(self::HTTP_PROTOCOL.$this->pub.'/', new EmptyVersionStrategy());

        return $package->getUrl($assetPath);
    }

    /**
     * If is CLI context returns absolute file path, otherwise returns absolute URL path.
     */
    public function getAbsoluteAssetPathByContext($assetPath)
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
    public function getRelativeAssetPathContextIndependent($assetPath)
    {
        $package = new UrlPackage('/', new EmptyVersionStrategy());

        return $package->getUrl($assetPath);
    }

    /**
     * If is CLI context returns absolute file path, otherwise returns relative URL path.
     */
    public function getRelativeAssetPathByContext($assetPath)
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
    public function getAbsoluteAssetFilePath($assetPath)
    {
        return $this->kernel->getProjectDir().DIRECTORY_SEPARATOR.'public'.$assetPath;
    }
}
