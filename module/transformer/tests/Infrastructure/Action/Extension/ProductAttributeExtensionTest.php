<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Tests\Infrastructure\Action\Extension;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Transformer\Infrastructure\Action\Extension\ProductAttributeExtension;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class ProductAttributeExtensionTest extends TestCase
{
    /**
     * @var AttributeQueryInterface
     */
    private $query;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var array
     */
    private $data;

    /**
     * @var Record|MockObject
     */
    private $record;

    /**
     * @var ProductAttributeExtension
     */
    private $extension;

    /**
     */
    protected function setUp()
    {
        $this->query = $this->createMock(AttributeQueryInterface::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->data = [];
        $this->record = $this->createMock(Record::class);
        $this->extension = new ProductAttributeExtension($this->query, $this->messageBus);
    }

    /**
     */
    public function testNoValuesExtend(): void
    {
        $this->record->expects($this->once())->method('has')->willReturn(false);
        $result = $this->extension->extend($this->record, $this->data);
        $this->assertEquals([], $result);
    }

    /**
     */
    public function testEmptyValueExtend()
    {
        $this->record->expects($this->once())->method('has')->willReturn(true);
        $this->record->expects($this->once())->method('getColumns')->willReturn(['color' => '']);

        $result = $this->extension->extend($this->record, $this->data);
        $this->assertEquals([], $result);
    }

    /**
     */
    public function testStringValueExtend()
    {
        $this->record->expects($this->once())->method('has')->willReturn(true);
        $this->record->expects($this->once())->method('getColumns')->willReturn(['color' => new StringValue('black')]);
        $this->query->expects($this->any())->method('findAttributeType')->willReturn(new AttributeType('SELECT'));
        $this->messageBus->expects($this->once())->method('dispatch')->willReturn(new Envelope($this->createMock(AttributeType::class)));
        $result = $this->extension->extend($this->record, $this->data);
        $this->assertEquals('black', $result['attributes']['color']->getValue());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'columns' => [
                    'color' => new StringValue('black'),
                ],
                'expected' => 'black',
            ],
            [
                'columns' => [
                    'color' => new StringCollectionValue([
                        'white',
                        'black',
                    ]),
                ],
                'expected' => ['white', 'black'],
            ],
        ];
    }
}
