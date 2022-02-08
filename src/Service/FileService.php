<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class FileService
{
    private UploaderHelper $uhs;
    private ParameterBagInterface $pb;

    public function __construct(UploaderHelper $uhs, ParameterBagInterface $pb)
    {
        $this->uhs = $uhs;
        $this->pb = $pb;
    }

    public function getUhs(): UploaderHelper
    {
        return $this->uhs;
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
        if ('image/jpg' === $this->getMimeType($entity, $attribute) || 'image/jpeg' === $this->getMimeType($entity, $attribute) || 'image/png' === $this->getMimeType($entity, $attribute) || 'image/gif' === $this->getMimeType($entity, $attribute)) {
            return true;
        }

        return false;
    }

    public function isPdf($entity, $attribute): bool
    {
        return 'application/pdf' === $this->getMimeType($entity, $attribute) || 'application/x-pdf' === $this->getMimeType($entity, $attribute);
    }
}
