<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Importer\Domain\Event\TransformerCreatedEvent;
use PHPUnit\Framework\TestCase;

class TransformerCreatedEventTest extends TestCase
{
    public function testEventCreate(): void
    {
        /** @var TransformerId $id */
        $id = $this->createMock(TransformerId::class);
        $name = 'Any Name';
        $key = 'Any Key';

        $result = new TransformerCreatedEvent($id, $name, $key);
        $this->assertEquals($id, $result->getAggregateId());
        $this->assertEquals($name, $result->getName());
        $this->assertEquals($key, $result->getKey());
    }
}
