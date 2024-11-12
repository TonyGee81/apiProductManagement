<?php

namespace App\Validator\Product;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PriceConstraint extends Constraint
{
    public string $message = 'The price must be positive and less 1000.';

    public function __construct(?string $mode = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->message = $message ?? $this->message;
    }
}