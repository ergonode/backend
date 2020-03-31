<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Multimedia\Tests\Infrastructure\Provider;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Infrastructure\Provider\SimpleMultimediaFileProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Kernel;

/**
 */
class SimpleMultimediaFileProviderTest extends TestCase
{
    /**
     */
    public function testFileGenerate(): void
    {
        /** @var MultimediaId|MockObject $id */
        $filename = 'FILENAME';
        /** @var Kernel|MockObject $kernel */
        $kernel = $this->createMock(Kernel::class);
        $kernel->method('getProjectDir')->willReturn('DIRECTORY');
        /** @var Multimedia|MockObject $multimedia */
        $multimedia = $this->createMock(Multimedia::class);
        $multimedia->method('getFileName')->willReturn($filename);


        $provider = new SimpleMultimediaFileProvider($kernel);
        $result = $provider->getFile($multimedia->getFileName());

        $this->assertSame('DIRECTORY/public/multimedia/FILENAME', $result);
    }
}
