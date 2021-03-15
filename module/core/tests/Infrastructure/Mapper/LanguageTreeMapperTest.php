<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Infrastructure\Mapper;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Core\Infrastructure\Mapper\LanguageTreeMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class LanguageTreeMapperTest extends TestCase
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
        $id = Uuid::uuid4()->toString();
        $treeLanguages = [
            'en_GB' => [
                'id' => $id,
                'code' => 'en_GB',
                'parent_id' => null,
                'level' => 0,
            ],
        ];

        $privileges =
            [
                'en_GB' => new LanguagePrivileges(true, false),
            ];

        $mapper = new LanguageTreeMapper($this->translator);
        $result = $mapper->map(
            $this->language,
            $treeLanguages,
            $privileges
        );

        self::assertIsArray($result);
        self::assertArrayHasKey('en_GB', $result);
        self::assertArrayHasKey('id', $result['en_GB']);
        self::assertArrayHasKey('code', $result['en_GB']);
        self::assertArrayHasKey('level', $result['en_GB']);
        self::assertArrayHasKey('name', $result['en_GB']);
        self::assertArrayHasKey('parent_id', $result['en_GB']);
        self::assertArrayHasKey('privileges', $result['en_GB']);
        self::assertEquals($id, $result['en_GB']['id']);
        self::assertEquals('en_GB', $result['en_GB']['code']);
    }
}
