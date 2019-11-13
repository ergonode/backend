<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Event;

use Ergonode\Comment\Domain\Event\CommentContentChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 */
class CommentContentChangedEventTest extends TestCase
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

        $command = new CommentContentChangedEvent($from, $to, $editedAt);
        $this->assertSame($editedAt, $command->getEditedAt());
        $this->assertSame($from, $command->getFrom());
        $this->assertSame($to, $command->getTo());
    }
}
