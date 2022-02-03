<?php

namespace App\Entity;

use App\Entity\Traits\DescriptionTrait;
use App\Entity\Traits\ImageTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table()
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="App\Repository\ServiceRepository")
 */
class Service extends AbstractBase
{
    use DescriptionTrait;
    use ImageTrait;
    use PositionTrait;
    use SlugTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private string $slug;

    /**
     * @Vich\UploadableField(mapping="service", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/jpg", "image/jpeg", "image/png", "image/gif"}
     * )
     * @Assert\Image(minWidth=1200)
     */
    private ?File $imageFile;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getTitle() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
