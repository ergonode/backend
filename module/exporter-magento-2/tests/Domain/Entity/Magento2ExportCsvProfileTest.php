<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Tests\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2CsvChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class Magento2ExportCsvProfileTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

    private string $name;
    private string $filename;

    /**
     * @var Language|MockObject
     */
    private Language $defaultLanguage;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Any Name';
        $this->filename = 'anyfile.csv';
        $this->defaultLanguage = $this->createMock(Language::class);
    }

    public function testCreateEntity(): void
    {
        $entity = new Magento2CsvChannel(
            $this->id,
            $this->name,
            $this->filename,
            $this->defaultLanguage
        );

        self::assertEquals($this->id, $entity->getId());
        self::assertEquals('magento-2-csv', $entity->getType());
        self::assertEquals($this->name, $entity->getName());
        self::assertEquals($this->filename, $entity->getFilename());
        self::assertEquals($this->defaultLanguage, $entity->getDefaultLanguage());
    }

    /**
     * @throws \Exception
     */
    public function testSetEntity(): void
    {
        $entity = new Magento2CsvChannel(
            $this->id,
            $this->name,
            $this->filename,
            $this->defaultLanguage
        );

        $id = $this->createMock(ChannelId::class);
        $name = 'New Name';
        $filename = 'new_file_name';
        $defaultLanguage = $this->createMock(Language::class);

        $entity->setName($name);
        $entity->setFilename($filename);
        $entity->setDefaultLanguage($defaultLanguage);


        self::assertEquals($id, $entity->getId());
        self::assertEquals($name, $entity->getName());
        self::assertEquals($filename, $entity->getFilename());
        self::assertEquals($defaultLanguage, $entity->getDefaultLanguage());
    }
}
