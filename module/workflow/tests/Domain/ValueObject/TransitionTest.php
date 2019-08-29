<?php

namespace Ergonode\Workflow\Tests\Domain\ValueObject;

use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use PHPUnit\Framework\TestCase;

class TransitionTest extends TestCase
{
    /**
     */
    public function testObjectCreation(): void
    {
        $name = 'Any name';
        /** @var StatusId $source */
        $source = $this->createMock(StatusId::class);
        /** @var StatusId $destination */
        $destination = $this->createMock(StatusId::class);

        $status = new Transition($name, $source, $destination);
        $this->assertSame($name, $status->getName());
        $this->assertSame($source, $status->getSource());
        $this->assertSame($destination, $status->getDestination());
    }
}
