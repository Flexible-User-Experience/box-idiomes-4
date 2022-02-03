<?php

namespace App\Entity;

use App\Entity\Traits\BankCreditorSepaTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\Table(name="person")
 */
class Person extends AbstractPerson
{
    use BankCreditorSepaTrait;

    /**
     * @ORM\Column(type="string")
     */
    protected ?string $dni;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="parent")
     */
    private Collection $students;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Bank", cascade={"persist"})
     * @Assert\Valid
     */
    protected ?Bank $bank = null;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function setStudents(Collection $students): self
    {
        $this->students = $students;

        return $this;
    }

    public function getSonsAmount(): int
    {
        return count($this->students);
    }

    public function getEnabledSonsAmount(): int
    {
        $result = 0;
        /** @var Student $student */
        foreach ($this->getStudents() as $student) {
            if ($student->getEnabled() && !$student->getIsPaymentExempt()) {
                ++$result;
            }
        }

        return $result;
    }
}
