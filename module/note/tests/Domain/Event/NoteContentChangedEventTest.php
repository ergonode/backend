<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Domain\Event;

use Ergonode\Note\Domain\Event\NoteContentChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class NoteContentChangedEventTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        $from = 'Any content from';
        $to = 'Any content to';
        /** @var \DateTime $editedAt */
        $editedAt = $this->createMock(\DateTime::class);

        $command = new NoteContentChangedEvent($from, $to, $editedAt);
        $this->assertSame($editedAt, $command->getEditedAt());
        $this->assertSame($from, $command->getFrom());
        $this->assertSame($to, $command->getTo());
    }
}
