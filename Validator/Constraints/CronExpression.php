<?php

namespace Dukecity\CommandSchedulerBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class CronExpression.
 */
class CronExpression extends Constraint
{
    /**
     * Constraint error message.
     */
    public string $message;
}
