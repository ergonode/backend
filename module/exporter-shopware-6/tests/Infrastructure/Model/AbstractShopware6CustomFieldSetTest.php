<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldSetConfig;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractShopware6CustomFieldSetTest extends TestCase
{
    private string $id;

    private string $name;

    /**
     * @var AbstractShopware6CustomFieldSetConfig|MockObject
     */
    private AbstractShopware6CustomFieldSetConfig $config;

    private array $relations;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->config = $this->createMock(AbstractShopware6CustomFieldSetConfig::class);
        $this->relations = [];
    }

    public function testCreateModel(): void
    {
        $model = $this->getClass();

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->config, $model->getConfig());
        self::assertEquals($this->relations, $model->getRelations());
        self::assertNotTrue($model->isModified());
    }

    public function testSetModel(): void
    {
        $model = $this->getClass();

        $name = 'new_name';
        $model->setName($name);
        $model->addRelation($this->relations);


        self::assertEquals($name, $model->getName());
        self::assertEquals([$this->relations], $model->getRelations());
        self::assertTrue($model->isModified());
    }

    private function getClass(): AbstractShopware6CustomFieldSet
    {
        return new class(
            $this->id,
            $this->name,
            $this->config,
            $this->relations
        ) extends AbstractShopware6CustomFieldSet {
        };
    }
}
