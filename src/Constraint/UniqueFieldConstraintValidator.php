<?php

namespace WeboDeliveryTime\Constraint;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueFieldConstraintValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $repository = $this->entityManager->getRepository($constraint->entityClass);
        $existingEntity = $repository->findOneBy([$constraint->fieldName => $value]);

        if ($existingEntity) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}