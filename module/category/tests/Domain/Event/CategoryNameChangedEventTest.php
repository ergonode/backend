<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Event;

use Ergonode\Category\Domain\Event\CategoryNameChangedEvent;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryNameChangedEventTest extends TestCase
{
    /**
     */
    public function testEvent(): void
    {
        $from = $this->createMock(TranslatableString::class);
        $to = $this->createMock(TranslatableString::class);

        $command = new CategoryNameChangedEvent($from, $to);
        $this->assertEquals($from, $command->getFrom());
        $this->assertEquals($to, $command->getTo());
    }
}
