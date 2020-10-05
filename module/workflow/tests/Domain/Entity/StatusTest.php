<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Entity;

use Ergonode\Workflow\Domain\Entity\Status;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;

/**
 */
class StatusTest extends TestCase
{
    /**
     * @var StatusId|MockObject
     */
    private StatusId $id;

    /**
     * @var StatusCode|MockObject
     */
    private StatusCode $code;

    /**
     * @var Color|MockObject
     */
    private Color $color;

    /**
     * @var TranslatableString|MockObject
     */
    private TranslatableString $name;

    /**
     * @var TranslatableString|MockObject
     */
    private TranslatableString $description;

    /**
     */
    protected function setUp(): void
    {
        $this->id = $this->createMock(StatusId::class);
        $this->code = $this->createMock(StatusCode::class);
        $this->color = $this->createMock(Color::class);
        $this->name = $this->createMock(TranslatableString::class);
        $this->description = $this->createMock(TranslatableString::class);
    }


    /**
     * @throws \Exception
     */
    public function testCreation(): void
    {
        $status = $this->getClass();

        self::assertSame($this->id, $status->getId());
        self::assertSame($this->code, $status->getCode());
        self::assertSame($this->color, $status->getColor());
        self::assertSame($this->name, $status->getName());
        self::assertSame($this->description, $status->getDescription());
    }

    /**
     * @throws \Exception
     */
    public function testDifferentColorManipulation(): void
    {
        $color = $this->createMock(Color::class);
        $color->expects(self::once())->method('isEqual')->willReturn(false);
        $status = $this->getClass();
        $status->changeColor($color);

        self::assertSame($color, $status->getColor());
        self::assertNotSame($this->color, $status->getColor());
    }

    /**
     * @throws \Exception
     */
    public function testSameColorManipulation(): void
    {
        $color = $this->createMock(Color::class);
        $color->expects(self::once())->method('isEqual')->willReturn(true);
        $status = $this->getClass();
        $status->changeColor($color);

        self::assertSame($this->color, $status->getColor());
        self::assertNotSame($color, $status->getColor());
    }

    /**
     * @throws \Exception
     */
    public function testDifferentNameManipulation(): void
    {
        $name = $this->createMock(TranslatableString::class);
        $name->expects(self::once())->method('isEqual')->willReturn(false);
        $status = $this->getClass();
        $status->changeName($name);

        self::assertSame($name, $status->getName());
        self::assertNotSame($this->name, $status->getName());
    }

    /**
     * @throws \Exception
     */
    public function testSameNameManipulation(): void
    {
        $name = $this->createMock(TranslatableString::class);
        $name->expects(self::once())->method('isEqual')->willReturn(true);
        $status = $this->getClass();
        $status->changeName($name);

        self::assertSame($this->name, $status->getName());
        self::assertNotSame($name, $status->getName());
    }

    /**
     * @throws \Exception
     */
    public function testDifferentDescriptionManipulation(): void
    {
        $description = $this->createMock(TranslatableString::class);
        $description->expects(self::once())->method('isEqual')->willReturn(false);
        $status = $this->getClass();
        $status->changeDescription($description);

        self::assertSame($description, $status->getDescription());
        self::assertNotSame($this->name, $status->getDescription());
    }

    /**
     * @throws \Exception
     */
    public function testSameDescriptionManipulation(): void
    {
        $description = $this->createMock(TranslatableString::class);
        $description->expects(self::once())->method('isEqual')->willReturn(true);
        $status = $this->getClass();
        $status->changeDescription($description);

        self::assertSame($this->description, $status->getDescription());
        self::assertNotSame($description, $status->getDescription());
    }

    /**
     * @return Status
     *
     * @throws \Exception
     */
    private function getClass(): Status
    {
        return new Status($this->id, $this->code, $this->color, $this->name, $this->description);
    }
}
