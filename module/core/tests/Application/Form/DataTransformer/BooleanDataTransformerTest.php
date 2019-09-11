<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Application\Form\DataTransformer;

use Ergonode\Core\Application\Form\DataTransformer\BooleanDataTransformer;
use PHPUnit\Framework\TestCase;

/**
 */
class BooleanDataTransformerTest extends TestCase
{

    /**
     */
    public function testTransform()
    {
        $transformer = new BooleanDataTransformer();
        $this->assertEquals(1, $transformer->transform('true'));
        $this->assertEquals(false, $transformer->transform('false'));
    }

    /**
     */
    public function testReverseTransform()
    {
        $transformer = new BooleanDataTransformer();
        $this->assertEquals('true', $transformer->reverseTransform('true'));
        $this->assertEquals('true', $transformer->reverseTransform(true));
        $this->assertEquals('true', $transformer->reverseTransform(1));
        $this->assertEquals('false', $transformer->reverseTransform(0));
        $this->assertEquals('false', $transformer->reverseTransform('false'));
        $this->assertEquals('false', $transformer->reverseTransform(false));
        $this->expectExceptionMessage('Expect boolean');
        $this->assertEquals('false', $transformer->reverseTransform('fadwwalse'));
    }
}
