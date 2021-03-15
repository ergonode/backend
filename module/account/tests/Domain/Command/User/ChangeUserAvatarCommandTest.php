<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Command\User;

use Ergonode\Account\Domain\Command\User\ChangeUserAvatarCommand;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class ChangeUserAvatarCommandTest extends TestCase
{
    public function testCreateCommand(): void
    {
        /** @var UserId|MockObject $id */
        $id = $this->createMock(UserId::class);
        /** @var File|MockObject $file */
        $file = $this->createMock(File::class);
        $command = new ChangeUserAvatarCommand($id, $file);

        $this->assertEquals($id, $command->getId());
        $this->assertEquals($file, $command->getFile());
    }
}
