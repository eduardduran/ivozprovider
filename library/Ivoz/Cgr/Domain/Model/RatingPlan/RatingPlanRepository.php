<?php

namespace Ivoz\Cgr\Domain\Model\RatingPlan;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Collections\Selectable;

interface RatingPlanRepository extends ObjectRepository, Selectable
{

}

