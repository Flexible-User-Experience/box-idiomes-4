<?php

namespace App\Entity;

use App\Entity\Traits\BankCreditorSepaTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Person.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\Table(name="person")
 */
class Person extends AbstractPerson
{
    use BankCreditorSepaTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $dni;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="parent")
     */
    private $students;

    /**
     * @var Bank
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Bank", cascade={"persist"})
     * @Assert\Valid
     */
    protected $bank;

    /**
     * Methods.
     */

    /**
     * Person constructor.
     */
    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * @param array $students
     *
     * @return Person
     */
    public function setStudents($students)
    {
        $this->students = $students;

        return $this;
    }

    /**
     * @return int
     */
    public function getSonsAmount()
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
