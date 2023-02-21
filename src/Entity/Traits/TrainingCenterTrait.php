<?php

namespace App\Entity\Traits;

use App\Entity\TrainingCenter;
use Doctrine\ORM\Mapping as ORM;

trait TrainingCenterTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TrainingCenter")
     *
     * @ORM\JoinColumn(name="training_center_id", referencedColumnName="id", nullable=true)
     */
    protected ?TrainingCenter $trainingCenter = null;

    public function getTrainingCenter(): ?TrainingCenter
    {
        return $this->trainingCenter;
    }

    public function setTrainingCenter(?TrainingCenter $trainingCenter): self
    {
        $this->trainingCenter = $trainingCenter;

        return $this;
    }
}
