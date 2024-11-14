<?php

namespace App\Validator\Product;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class TypeConstraint extends Constraint
{
    public string $message = 'The product name must be have only character.';

    public function __construct(?string $mode = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->message = $message ?? $this->message;
    }
}
