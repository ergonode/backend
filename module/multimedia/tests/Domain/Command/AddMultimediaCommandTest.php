<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Domain\Command;

use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddMultimediaCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testMultimediaCreate(): void
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $this->createMock(UploadedFile::class);
        $id = MultimediaId::generate();

        $command = new AddMultimediaCommand($id, $uploadedFile);
        $this->assertEquals($id, $command->getId());
        $this->assertEquals($uploadedFile, $command->getFile());
    }
}
