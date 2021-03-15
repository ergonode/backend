<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Dictionary;

use Ergonode\ExporterFile\Infrastructure\Dictionary\WriterTypeDictionary;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterTypeProvider;
use Symfony\Contracts\Translation\TranslatorInterface;

class WriterTypeDictionaryTest extends TestCase
{
    public function testDictionary(): void
    {
        $provider = $this->createMock(WriterTypeProvider::class);
        $provider->expects($this->once())->method('provide')->willReturn(['type']);
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->expects($this->once())->method('trans')->willReturn('translated');

        $dictionary = new WriterTypeDictionary($provider, $translator);
        $result = $dictionary->dictionary();
        self::assertSame(['type' => 'translated'], $result);
    }
}
