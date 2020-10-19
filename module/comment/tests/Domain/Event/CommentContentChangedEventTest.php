<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Event;

use Ergonode\Comment\Domain\Event\CommentContentChangedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
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
        /** @var CommentId $id */
        $id = $this->createMock(CommentId::class);
        $from = 'Any content from';
        $to = 'Any content to';
        /** @var \DateTime $editedAt */
        $editedAt = $this->createMock(\DateTime::class);

        $command = new CommentContentChangedEvent($id, $from, $to, $editedAt);
        self::assertSame($id, $command->getAggregateId());
        self::assertSame($editedAt, $command->getEditedAt());
        self::assertSame($from, $command->getFrom());
        self::assertSame($to, $command->getTo());
    }
}
