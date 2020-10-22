<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Command;

use Ergonode\Comment\Domain\Command\UpdateCommentCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CommentId;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateCommentCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var CommentId $commentId */
        $commentId = $this->createMock(CommentId::class);
        /** @var Uuid $objectId */
        $content = 'Any content';

        $command = new UpdateCommentCommand($commentId, $content);
        $this->assertSame($commentId, $command->getId());
        $this->assertSame($content, $command->getContent());
    }
}
