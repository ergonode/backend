<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Tests\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class Magento2ExportCsvProfileTest extends TestCase
{
    /**
     * @var ExportProfileId|MockObject
     */
    private ExportProfileId $id;

    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $filename;

    /**
     * @var Language|MockObject
     */
    private Language $defaultLanguage;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(ExportProfileId::class);
        $this->name = 'Any Name';
        $this->filename = 'anyfile.csv';
        $this->defaultLanguage = $this->createMock(Language::class);
    }

    /**
     */
    public function testCreateEntity(): void
    {
        $entity = new Magento2ExportCsvProfile(
            $this->id,
            $this->name,
            $this->filename,
            $this->defaultLanguage
        );

        $this->assertEquals($this->id, $entity->getId());
        $this->assertEquals('magento-2-csv', $entity->getType());
        $this->assertEquals($this->name, $entity->getName());
        $this->assertEquals($this->filename, $entity->getFilename());
        $this->assertEquals($this->defaultLanguage, $entity->getDefaultLanguage());
    }

    /**
     */
    public function testSetEntity(): void
    {
        $entity = new Magento2ExportCsvProfile(
            $this->id,
            $this->name,
            $this->filename,
            $this->defaultLanguage
        );

        $id = $this->createMock(ExportProfileId::class);
        $name = 'New Name';
        $filename = 'new_file_name';
        $defaultLanguage = $this->createMock(Language::class);

        $entity->setName($name);
        $entity->setFilename($filename);
        $entity->setDefaultLanguage($defaultLanguage);


        $this->assertEquals($id, $entity->getId());
        $this->assertEquals($name, $entity->getName());
        $this->assertEquals($filename, $entity->getFilename());
        $this->assertEquals($defaultLanguage, $entity->getDefaultLanguage());
    }
}
