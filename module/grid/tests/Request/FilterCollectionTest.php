<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Tests\Request;

use Ergonode\Grid\Request\FilterCollection;
use PHPUnit\Framework\TestCase;

/**
 */
class FilterCollectionTest extends TestCase
{
    /**
     */
    public function testCreateCollection(): void
    {
        $string = 'key1=value1;key2=value2,value3;key3:PL=value4';
        $collection = new FilterCollection($string);
        $this->assertEquals(['=' => 'value1'], $collection->get('key1'));
        $this->assertEquals(['=' => 'value2,value3'], $collection->get('key2'));
        $this->assertEquals(['=' => 'value4'], $collection->get('key3:PL'));
        $this->assertEquals(['=' => 'value4'], $collection->get('key3:PL'));
    }

    /**
     */
    public function testReturnValueForNotExistKey(): void
    {
        $collection = new FilterCollection();
        $this->assertEquals([], $collection->get('key1'));
    }
}
