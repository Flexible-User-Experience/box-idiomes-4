<?php

namespace App\Entity;

use App\Entity\Traits\BankCreditorSepaTrait;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var array
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="parent")
     */
    private $students;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Bank", cascade={"persist"})
     * @Assert\Valid
     */
    protected ?Bank $bank = null;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getStudents()
    {
        return $this->students;
    }

    public function setStudents($students)
    {
        $this->students = $students;

        return $this;
    }

    public function getSonsAmount(): int
    {
        return count($this->students);
    }

    /**
     * @return int
     */
    public function getEnabledSonsAmount()
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
