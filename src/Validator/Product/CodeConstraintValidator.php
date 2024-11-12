<?php

namespace App\Validator\Product;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

#[\Attribute]
class CodeConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PriceConstraint) {
            throw new UnexpectedTypeException($constraint, PriceConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (count($value) > 6) {
            $this->context->buildViolation($constraint->message);
        }

    }
}