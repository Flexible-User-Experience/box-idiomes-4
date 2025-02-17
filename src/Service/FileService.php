<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final readonly class FileService
{
    public function __construct(
        private UploaderHelper $uhs,
        private ParameterBagInterface $pb,
    ) {
    }

    public function getMimeType($entity, $attribute): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $path = $this->pb->get('kernel.project_dir').DIRECTORY_SEPARATOR.'public'.$this->uhs->asset($entity, $attribute);
        $mimeType = finfo_file($finfo, $path);
        finfo_close($finfo);

        return $mimeType;
    }

    public function isImage($entity, $attribute): string
    {
        return 'image/jpg' === $this->getMimeType($entity, $attribute) || 'image/jpeg' === $this->getMimeType($entity, $attribute) || 'image/png' === $this->getMimeType($entity, $attribute) || 'image/gif' === $this->getMimeType($entity, $attribute);
    }

    public function isPdf($entity, $attribute): bool
    {
        return 'application/pdf' === $this->getMimeType($entity, $attribute) || 'application/x-pdf' === $this->getMimeType($entity, $attribute);
    }
}
