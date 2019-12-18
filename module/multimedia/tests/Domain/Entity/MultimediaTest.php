<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\Entity;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
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
        $multimediaId = $this->createMock(MultimediaId::class);
        $name = 'name';
        $ext = 'extension';
        $size = 123;
        $crc = 'afd';
        $mime = 'text/json';
        $multimedia = new Multimedia(
            $multimediaId,
            $name,
            $ext,
            $size,
            $crc,
            $mime
        );

        $this->assertEquals($multimediaId, $multimedia->getId());
        $this->assertEquals(sprintf('%s.%s', $multimediaId, $ext), $multimedia->getFileName());
        $this->assertEquals($name, $multimedia->getName());
        $this->assertEquals($ext, $multimedia->getExtension());
        $this->assertEquals($size, $multimedia->getSize());
        $this->assertEquals($mime, $multimedia->getMime());
    }
}
