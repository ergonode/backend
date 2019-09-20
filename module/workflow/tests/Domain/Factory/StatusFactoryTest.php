<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Factory\StatusFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class StatusFactoryTest extends TestCase
{
    /**
     * @var StatusId|MockObject
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var Color|MockObject
     */
    private $color;

    /**
     * @var TranslatableString|MockObject
     */
    private $name;

    /**
     * @var TranslatableString|MockObject
     */
    private $description;

    /**
     */
    protected function setUp()
    {
        $this->id = $this->createMock(StatusId::class);
        $this->color = $this->createMock(Color::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->description = $this->createMock(TranslatableString::class);
        $this->code = 'Any code';
    }

    /**
     * @throws \Exception
     */
    public function testCreateObject(): void
    {
        $factory = new StatusFactory();
        $status  = $factory->create($this->id, $this->code, $this->color, $this->name, $this->description);
        $this->assertNotNull($status);
        $this->assertSame($this->id, $status->getId());
        $this->assertSame($this->code, $status->getCode());
        $this->assertSame($this->color, $status->getColor());
        $this->assertSame($this->name, $status->getName());
        $this->assertSame($this->description, $status->getDescription());
    }
}
