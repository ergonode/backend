<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Factory\StatusFactory;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class StatusFactoryTest extends TestCase
{
    /**
     * @var StatusCode
     */
    private StatusCode $code;

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
    protected function setUp(): void
    {
        $this->color = $this->createMock(Color::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->description = $this->createMock(TranslatableString::class);
        $this->code = $this->createMock(StatusCode::class);
    }

    /**
     * @throws \Exception
     */
    public function testCreateObject(): void
    {
        $factory = new StatusFactory();
        $status  = $factory->create($this->code, $this->color, $this->name, $this->description);
        $this->assertNotNull($status);
        $this->assertEquals(StatusId::fromCode($this->code->getValue()), $status->getId());
        $this->assertSame($this->code, $status->getCode());
        $this->assertSame($this->color, $status->getColor());
        $this->assertSame($this->name, $status->getName());
        $this->assertSame($this->description, $status->getDescription());
    }
}
