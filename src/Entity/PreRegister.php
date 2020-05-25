<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PreRegister.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\PreRegisterRepository")
 * @ORM\Table(name="pre_register")
 */
class PreRegister extends AbstractPerson
{
}
