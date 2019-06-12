<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\ValueObject;

use Ergonode\Product\Domain\ValueObject\ProductStatus;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductStatusTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param string $status
     */
    public function testValidValue(string $status): void
    {
        $status = new ProductStatus($status);

        $this->assertEquals($status, $status->getValue());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidValue(): void
    {
        new ProductStatus('Any invalid status');
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return array_map(
            function ($element) {
                return [$element];
            },
            ProductStatus::AVAILABLE
        );
    }
}
