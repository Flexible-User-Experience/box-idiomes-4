<?php

namespace App\Service;

use App\Entity\ContactMessage;
use App\Entity\Teacher;
use App\Kernel;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final readonly class SmartAssetsHelperService
{
    public const string HTTP_PROTOCOL = 'https://';

    private Filesystem $filesystem;
    private string $pub;
    private string $amd;
    private string $bba;
    private string $bpn;
    private string $assetsPathDir;
    private string $publicAssetsPathDir;

    public function __construct(
        private UploaderHelper $uh,
        private CacheManager $icm,
        private ParameterBagInterface $pb,
    ) {
        $this->filesystem = new Filesystem();
        $this->pub = $pb->get('project_url_base');
        $this->amd = $pb->get('mailer_destination');
        $this->bba = $pb->get('boss_address');
        $this->bpn = $pb->get('boss_phone_number_1');
        $this->assetsPathDir = $pb->get('kernel.project_dir').DIRECTORY_SEPARATOR.'assets';
        $this->publicAssetsPathDir = $pb->get('kernel.project_dir').DIRECTORY_SEPARATOR.'public';
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

    public function getContactMessageAttatchmentPath(ContactMessage $contactMessage): string
    {
        return $this->uh->asset($contactMessage, 'documentFile');
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
     * Always return relative URL path, even in CLI contexts.
     */
    public function getRelativeAssetPathContextIndependent($assetPath): string
    {
        return (new UrlPackage('/', new EmptyVersionStrategy()))->getUrl($assetPath);
    }

    /**
     * Returns absolute file path.
     */
    public function getAbsoluteAssetFilePath($assetPath): string
    {
        return $this->publicAssetsPathDir.$assetPath;
    }

    public function getLocalAssetsPath(string $path): string
    {
        $publicPath = sprintf('%s%s', $this->assetsPathDir, $path);
        if (!$this->fileExists($publicPath)) {
            throw new FileNotFoundException();
        }

        return $publicPath;
    }

    public function fileExists(string $filepath): bool
    {
        return $this->filesystem->exists($filepath);
    }
}
