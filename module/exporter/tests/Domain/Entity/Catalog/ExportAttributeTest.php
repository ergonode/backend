<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Exporter\Tests\Domain\Entity\Catalog;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportAttribute;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 */
class ExportAttributeTest extends TestCase
{
    /**
     * @var Uuid
     */
    private Uuid $id;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var TranslatableString|string
     */
    private TranslatableString $name;

    /**
     */
    protected function setUp(): void
    {
        $this->code = 'CODE';
        $this->id = Uuid::uuid4();
        $this->name = new TranslatableString(['en_GB' => 'english', 'pl_PL' => 'polish']);
    }

    /**
     */
    public function testConstructor(): void
    {
        $type = 'TYPE';
        $attribute = $this->getExportAttribute($type);

        self::assertEquals($this->id, $attribute->getId());
        self::assertEquals($this->code, $attribute->getCode());
        self::assertEquals($this->name, $attribute->getName());
        self::assertEquals($type, $attribute->getType());
        self::assertTrue($attribute->isMultilingual());
        self::assertIsArray($attribute->getParameters());
        self::assertFalse($attribute->isSystem());
    }

    /**
     */
    public function testMethod(): void
    {
        $attribute = $this->getExportAttribute('SELECT');
        $attribute->changeOrCreateOption(
            'opt_1',
            'v1'
        );
        $attribute->changeOrCreateOption(
            'opt_2',
            'v2'
        );

        self::assertIsArray($attribute->getParameters());
        self::assertArrayHasKey('options', $attribute->getParameters());
        self::assertCount(2, $attribute->getParameters()['options']);
    }

    /**
     * @param string $type
     *
     * @return ExportAttribute
     */
    private function getExportAttribute(string $type): ExportAttribute
    {
        return new ExportAttribute(
            $this->id,
            $this->code,
            $this->name,
            $type,
            true,
            [],
            false
        );
    }
}
