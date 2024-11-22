<?php

namespace App\Entity;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Product Management',
    attachables: [new OA\Attachable()]
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Management product'
)]
#[OA\License(name: 'MIT')]
class OpenApiSpec
{

}