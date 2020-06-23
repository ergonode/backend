<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Command;

use Ergonode\Multimedia\Domain\Command\AddAvatarCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 */
class AddAvatarCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testMultimediaCreate(): void
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $this->createMock(UploadedFile::class);
        $id = AvatarId::generate();

        $command = new AddAvatarCommand($id, $uploadedFile);
        $this->assertEquals($id, $command->getId());
        $this->assertEquals($uploadedFile, $command->getFile());
    }
}
