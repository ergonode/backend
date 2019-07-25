<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Tests\Domain\Event;

use Ergonode\Transformer\Domain\Event\TransformerConverterAddedEvent;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use PHPUnit\Framework\TestCase;

/**
 */
class TransformerConverterAddedEventTest extends TestCase
{
    /**
     */
    public function testEventCreate(): void
    {
        /** @var ConverterInterface $converter */
        $converter = $this->createMock(ConverterInterface::class);
        $collection = 'Any CollectionName';
        $filed = 'Any Field name';

        $result = new TransformerConverterAddedEvent($collection, $filed, $converter);
        $this->assertEquals($collection, $result->getCollection());
        $this->assertEquals($filed, $result->getField());
        $this->assertEquals($converter, $result->getConverter());
    }
}
