<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Tests\Domain\Command;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Note\Domain\Command\CreateNoteCommand;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class CreateNoteCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreation(): void
    {
        /** @var UserId $userId */
        $userId = $this->createMock(UserId::class);
        /** @var Uuid $objectId */
        $objectId = $this->createMock(Uuid::class);
        $content = 'Any content';

        $command = new CreateNoteCommand($userId, $objectId, $content);
        $this->assertSame($userId, $command->getAuthorId());
        $this->assertSame($objectId, $command->getObjectId());
        $this->assertSame($content, $command->getContent());
        $this->assertNotEmpty($command->getId());
    }
}
