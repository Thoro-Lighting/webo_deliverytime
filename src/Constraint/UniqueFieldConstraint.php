<?php

namespace WeboDeliveryTime\Constraint;

use Symfony\Component\Validator\Constraint;

class UniqueFieldConstraint extends Constraint
{
    public $message = 'This value is already used.';

    public $entityClass;

    public $fieldName;

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
