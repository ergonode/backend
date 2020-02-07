<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Entity;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class MultimediaTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testMultimediaCreate(): void
    {
        /** @var MultimediaId | MockObject $multimediaId */
        $multimediaId = $this->createMock(MultimediaId::class);
        $name = 'name';
        $ext = 'extension';
        $size = 123;
        $hash = $this->createMock(Hash::class);
        $mime = 'text/json';
        $multimedia = new Multimedia(
            $multimediaId,
            $name,
            $ext,
            $size,
            $hash,
            $mime
        );

        $this->assertEquals($multimediaId, $multimedia->getId());
        $this->assertEquals(sprintf('%s.%s', $multimediaId, $ext), $multimedia->getFileName());
        $this->assertEquals($name, $multimedia->getName());
        $this->assertEquals($ext, $multimedia->getExtension());
        $this->assertEquals($size, $multimedia->getSize());
        $this->assertEquals($mime, $multimedia->getMime());
        $this->assertEquals($hash, $multimedia->getHash());
    }
}
