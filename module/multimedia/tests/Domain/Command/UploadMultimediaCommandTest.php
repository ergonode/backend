<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Command;

use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Ergonode\Multimedia\Domain\Factory\MultimediaIdFactory;
use PHPUnit\Framework\TestCase;
use Ergonode\Multimedia\Domain\Command\UploadMultimediaCommand;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 */
class UploadMultimediaCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testMultimediaCreate(): void
    {
        $uploadedFile = $this->createMock(UploadedFile::class);
        $uploadedFile->method('getRealPath')
            ->willReturn(__FILE__);

        $command = new UploadMultimediaCommand('some name', $uploadedFile);
        $this->assertTrue(Uuid::isValid((string) $command->getId()));
        $this->assertEquals('some name', $command->getName());
        $this->assertEquals($uploadedFile, $command->getFile());
    }
}
