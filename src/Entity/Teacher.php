<?php

namespace App\Entity;

use App\Entity\Traits\DescriptionTrait;
use App\Entity\Traits\ImageTrait;
use App\Entity\Traits\PositionTrait;
use App\Entity\Traits\SlugTrait;
use App\Enum\TeacherColorEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table()
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="App\Repository\TeacherRepository")
 */
class Teacher extends AbstractBase
{
    use DescriptionTrait;
    use ImageTrait;
    use PositionTrait;
    use SlugTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private string $slug;

    /**
     * @Vich\UploadableField(mapping="teacher", fileNameProperty="imageName")
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/jpg", "image/jpeg", "image/png", "image/gif"}
     * )
     * @Assert\Image(allowLandscape=false, allowPortrait=true, minWidth=600)
     */
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private int $color = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $showInHomepage = true;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): int
    {
        return $this->color;
    }

    public function getCssColor(): string
    {
        return 'c-'.TeacherColorEnum::getReversedEnumArray()[$this->getColor()];
    }

    public function setColor(int $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function isShowInHomepage(): bool
    {
        return $this->showInHomepage;
    }

    public function setShowInHomepage(bool $showInHomepage): self
    {
        $this->showInHomepage = $showInHomepage;

        return $this;
    }

    public function __toString(): string
    {
        return $this->id ? $this->getName() : AbstractBase::DEFAULT_NULL_STRING;
    }
}
