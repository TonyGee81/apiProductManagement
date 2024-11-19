<?php

namespace App\Tests\Normalizer;

use App\Normalizer\PaginationNormalizer;
use Knp\Component\Pager\Pagination\PaginationInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[\AllowDynamicProperties]
class PaginationNormalizerTest extends TestCase
{
    protected function setUp(): void
    {
        $normalizer = $this->createMock(NormalizerInterface::class);
        $this->normalizer = new PaginationNormalizer($normalizer);
        $this->object = $this->createMock(PaginationInterface::class);
    }

    public function testNormalizeTest()
    {
        $this->assertInstanceOf(PaginationNormalizer::class, $this->normalizer);
    }

}
