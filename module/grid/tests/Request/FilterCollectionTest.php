<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
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
        $this->assertEquals('value1', $collection->getString('key1'));
        $this->assertEquals(['value1'], $collection->getArray('key1'));
        $this->assertEquals('value2,value3', $collection->getString('key2'));
        $this->assertEquals(['value2', 'value3'], $collection->getArray('key2'));
        $this->assertEquals('value4', $collection->getString('key3:PL'));
        $this->assertEquals(['value4'], $collection->getArray('key3:PL'));
    }

    /**
     */
    public function testReturnValueForNotExistKey(): void
    {
        $collection = new FilterCollection();
        $this->assertEquals(null, $collection->getString('key1'));
        $this->assertEquals([], $collection->getArray('key1'));
    }

    /**
     */
    public function testReturnDefaultValueForNotExistKey(): void
    {
        $collection = new FilterCollection();
        $this->assertEquals('default', $collection->getString('key1','default'));
        $this->assertEquals(['default1','default2'], $collection->getArray('key1', ['default1','default2']));
    }
}
