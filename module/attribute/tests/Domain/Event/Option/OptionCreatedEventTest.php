<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Event\Option;

use Ergonode\Attribute\Domain\Event\Option\OptionCreatedEvent;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateId;
use PHPUnit\Framework\TestCase;

class OptionCreatedEventTest extends TestCase
{
    public function testEventCreation(): void
    {
        /** @var AggregateId $id */
        $id = $this->createMock(AggregateId::class);
        /** @var OptionKey $code */
        $code = $this->createMock(OptionKey::class);
        /** @var TranslatableString $label */
        $label = $this->createMock(TranslatableString::class);
        $event = new OptionCreatedEvent($id, $code, $label);
        $this->assertEquals($id, $event->getAggregateId());
        $this->assertEquals($code, $event->getCode());
        $this->assertEquals($label, $event->getLabel());
    }
}
