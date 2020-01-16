<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Tests\Domain\Command;

use Ergonode\Comment\Domain\Command\DeleteCommentCommand;
use Ergonode\Comment\Domain\Entity\CommentId;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteCommentCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var CommentId $CommentId */
        $commentId = $this->createMock(CommentId::class);

        $command = new DeleteCommentCommand($commentId);
        $this->assertSame($commentId, $command->getId());
    }
}
