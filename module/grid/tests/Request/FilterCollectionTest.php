<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Request;

use Ergonode\Grid\Request\FilterValue;
use Ergonode\Grid\Request\FilterValueCollection;
use PHPUnit\Framework\TestCase;

class FilterCollectionTest extends TestCase
{
    public function testCreateCollection(): void
    {
        $string = 'key1=value1;key2=value2,value3;key3:pl_PL=value4';
        $collection = new FilterValueCollection($string);
        $this->assertCount(3, $collection);
        foreach ($collection as $elements) {
            foreach ($elements as $element) {
                $this->assertInstanceOf(FilterValue::class, $element);
            }
        }
    }
}
