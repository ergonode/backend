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
        $this->name = new TranslatableString(['en' => 'english', 'pl' => 'polish']);
    }

    /**
     */
    public function testConstructor(): void
    {
        $type = 'TYPE';
        $attribute = $this->getExportAttribute($type);

        $this->assertEquals($this->id, $attribute->getId());
        $this->assertEquals($this->code, $attribute->getCode());
        $this->assertEquals($this->name, $attribute->getName());
        $this->assertEquals($type, $attribute->getType());
        $this->assertTrue($attribute->isMultilingual());
        $this->assertIsArray($attribute->getParameters());
        $this->assertFalse($attribute->isSystem());
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

        $this->assertIsArray($attribute->getParameters());
        $this->assertArrayHasKey('options', $attribute->getParameters());
        $this->assertCount(2, $attribute->getParameters()['options']);
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
