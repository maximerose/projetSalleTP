<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PhobieNumerique extends Constraint
{
    public $message = 'Le nombre "{{ nombre }}" est susceptible de générer une phobie par superstition.';
}