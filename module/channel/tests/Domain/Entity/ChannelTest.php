<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

class ChannelTest extends TestCase
{
    /**
     * @var ChannelId|MockObject
     */
    private ChannelId $id;

    private string $name;

    protected function setUp(): void
    {
        $this->id = $this->createMock(ChannelId::class);
        $this->name = 'Any Channel Name';
    }

    /**
     * @throws \Exception
     */
    public function testCreateEntity(): void
    {
        $entity = $this->getClass();

        self::assertSame($this->id, $entity->getId());
        self::assertSame($this->name, $entity->getName());
    }

    /**
     * @throws \Exception
     */
    public function testChangeName(): void
    {
        $entity = $this->getClass();

        $name = 'New Channel Name';
        $entity->setName($name);

        self::assertSame($this->id, $entity->getId());
        self::assertSame($name, $entity->getName());
    }

    private function getClass(): AbstractChannel
    {
        return new class(
            $this->id,
            $this->name,
        ) extends AbstractChannel {
            public static function getType(): string
            {
                return 'TYPE';
            }
        };
    }
}
