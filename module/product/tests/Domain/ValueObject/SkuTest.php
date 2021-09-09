<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\ValueObject;

use Ergonode\Product\Domain\ValueObject\Sku;
use PHPUnit\Framework\TestCase;

/**
 * Class SkuTest
 */
class SkuTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testGetValue(string $sku): void
    {
        $sku = new Sku($sku);

        $this->assertEquals($sku, $sku->getValue());
    }

    /**
     * @dataProvider invalidData
     */
    public function testInvalidValue(string $sku): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Sku($sku);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            ['B.AD46SB/BN926'],
        ];
    }

    /**
     * @return array
     */
    public function invalidData(): array
    {
        return [
            [str_repeat('a', 256)],
            [''],
        ];
    }
}
