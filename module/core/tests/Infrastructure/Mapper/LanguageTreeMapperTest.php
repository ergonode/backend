<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Infrastructure\Mapper;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Infrastructure\Mapper\LanguageTreeMapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
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

    /**
     */
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
            'en' => [
                'id' => $id,
                'code' => 'en',
                'parent_id' => null,
                'level' => 0,
            ],
        ];

        $privileges =
            [
                'en' => new LanguagePrivileges(true, false),
            ];

        $mapper = new LanguageTreeMapper($this->translator);
        $result = $mapper->map(
            $this->language,
            $treeLanguages,
            $privileges
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('en', $result);
        $this->assertArrayHasKey('id', $result['en']);
        $this->assertArrayHasKey('code', $result['en']);
        $this->assertArrayHasKey('level', $result['en']);
        $this->assertArrayHasKey('name', $result['en']);
        $this->assertArrayHasKey('parent_id', $result['en']);
        $this->assertArrayHasKey('privileges', $result['en']);
        $this->assertEquals($id, $result['en']['id']);
        $this->assertEquals('en', $result['en']['code']);
    }
}
