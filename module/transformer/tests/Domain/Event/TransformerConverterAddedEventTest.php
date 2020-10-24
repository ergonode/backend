<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Transformer\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Event\TransformerFieldAddedEvent;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use PHPUnit\Framework\TestCase;

class TransformerConverterAddedEventTest extends TestCase
{
    public function testEventCreate(): void
    {
        /** @var TransformerId $id */
        $id = $this->createMock(TransformerId::class);
        /** @var ConverterInterface $converter */
        $converter = $this->createMock(ConverterInterface::class);
        $filed = 'Any Field name';

        $result = new TransformerFieldAddedEvent($id, $filed, $converter);
        $this->assertEquals($id, $result->getAggregateId());
        $this->assertEquals($filed, $result->getField());
        $this->assertEquals($converter, $result->getConverter());
    }
}
