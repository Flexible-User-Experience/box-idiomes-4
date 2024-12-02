<?php

namespace App\Entity;

use App\Entity\Traits\BankCreditorSepaTrait;
use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[ORM\Table(name: 'person')]
class Person extends AbstractPerson
{
    use BankCreditorSepaTrait;

    #[ORM\Column(type: Types::STRING)]
    protected ?string $dni;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Student::class)]
    private Collection $students;

    #[ORM\OneToOne(targetEntity: Bank::class, cascade: ['persist'])]
    #[Assert\Valid]
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
