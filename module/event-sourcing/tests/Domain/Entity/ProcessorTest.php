<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Tests\Domain\Entity;

use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Transformer\Domain\Entity\Processor;
use Ergonode\Transformer\Domain\Entity\ProcessorId;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProcessorTest extends TestCase
{
    /**
     * @var ProcessorId|MockObject
     */
    private $id;

    /**
     * @var TransformerId|MockObject
     */
    private $transformerId;

    /**
     * @var ImportId|MockObject
     */
    private $importId;

    /**
     * @var string
     */
    private $action;

    /**
     *
     */
    protected function setUp()
    {
        $this->id = $this->createMock(ProcessorId::class);
        $this->transformerId = $this->createMock(TransformerId::class);
        $this->importId = $this->createMock(ImportId::class);
        $this->action = 'just string';
    }

    /**
     */
    public function testProcessorCreation(): void
    {
        $processor = new Processor($this->id, $this->transformerId, $this->importId, $this->action);
        $this->assertSame($this->id, $processor->getId());
        $this->assertSame($this->transformerId, $processor->getTransformerId());
        $this->assertSame($this->importId, $processor->getImportId());
        $this->assertSame($this->action, $processor->getAction());
        $this->assertTrue($processor->getStatus()->isCreated());
    }

    /**
     * @expectedException \LogicException
     */
    public function testProcess(): void
    {
        $processor = new Processor($this->id, $this->transformerId, $this->importId, $this->action);
        $processor->process();
        $processor->process();
    }

    /**
     * @expectedException \LogicException
     */
    public function testEnd()
    {
        $processor = new Processor($this->id, $this->transformerId, $this->importId, $this->action);
        $processor->process();
        $processor->end();
        $processor->end();
    }
    /**
     * @expectedException \LogicException
     */
    public function testStop()
    {
        $processor = new Processor($this->id, $this->transformerId, $this->importId, $this->action);
        $processor->stop();
        $processor->stop();
    }
}
