<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Infrastructure\Mapper;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Mapper\LanguageMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class LanguageMapperTest extends TestCase
{
    /**
     * @var MockObject|TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var Language|MockObject
     */
    private Language $language;

    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->language = $this->createMock(Language::class);
    }

    /**
     * @throws \Exception
     */
    public function testMapper(): void
    {
        $languages = [
            new Language('en_GB'),
        ];
        $mapper = new LanguageMapper($this->translator);
        $result = $mapper->map(
            $this->language,
            $languages
        );

        self::assertIsArray($result);
        self::assertArrayHasKey('en_GB', $result);
    }
}
