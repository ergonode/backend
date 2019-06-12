<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

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
     *
     * @param string $sku
     */
    public function testGetValue(string $sku): void
    {
        $sku = new Sku($sku);

        $this->assertEquals($sku, $sku->getValue());
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
}
