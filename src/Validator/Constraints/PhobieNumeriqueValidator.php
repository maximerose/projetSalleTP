<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhobieNumeriqueValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (($value == 13) || ($value == 666)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ nombre }}', $value)
                ->addViolation();
        }
    }
}