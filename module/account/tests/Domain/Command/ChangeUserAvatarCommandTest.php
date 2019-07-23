<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Domain\Command;

use Ergonode\Account\Domain\Command\ChangeUserAvatarCommand;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ChangeUserAvatarCommandTest extends TestCase
{
    /**
     */
    public function testCreateCommand(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        /** @var MultimediaId|MockObject $multimediaId */
        $multimediaId = $this->createMock(MultimediaId::class);
        $command = new ChangeUserAvatarCommand($id, $multimediaId);

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($multimediaId, $command->getAvatarId());
    }
}
