<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Reader\Tests\Infrastructure\Provider;

use Ergonode\Reader\Infrastructure\Processor\ReaderProcessorInterface;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use PHPUnit\Framework\TestCase;

/**
 */
class ReaderProcessorProviderTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testProviderNotFoundReader(): void
    {
        $provider = new ReaderProcessorProvider();
        $provider->getReader('any reader');
    }

    /**
     * @param string                   $key
     * @param ReaderProcessorInterface $reader
     *
     * @dataProvider getReaderCollection
     */
    public function testProviderFoundReader(string $key, ReaderProcessorInterface $reader): void
    {
        $provider = new ReaderProcessorProvider();
        $provider->setReader($key, $reader);

        $this->assertEquals($reader, $provider->getReader($key));
    }

    /**
     * @return array
     */
    public function getReaderCollection(): array
    {
        return [
            [
                'First Key',
                $this->createMock(ReaderProcessorInterface::class),
            ],
            [
                'second Key',
                $this->createMock(ReaderProcessorInterface::class),
            ],
        ];
    }
}
