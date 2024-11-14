<?php

namespace App\Validator\Product;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CodeConstraint extends Constraint
{
    public string $message = 'The product code must be at maximum length of 6 characters.';

    public function __construct(?string $mode = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->message = $message ?? $this->message;
    }
}
