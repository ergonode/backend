<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Command;

use PHPUnit\Framework\TestCase;
use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 */
class AddMultimediaCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testMultimediaCreate(): void
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $this->createMock(UploadedFile::class);

        $command = new AddMultimediaCommand('some name', $uploadedFile);
        $this->assertTrue(Uuid::isValid((string) $command->getId()));
        $this->assertEquals('some name', $command->getName());
        $this->assertEquals($uploadedFile, $command->getFile());
    }
}
