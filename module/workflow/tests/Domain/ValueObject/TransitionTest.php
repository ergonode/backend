<?php

namespace Ergonode\Workflow\Tests\Domain\ValueObject;

use Ergonode\Workflow\Domain\ValueObject\Transition;
use Ergonode\Workflow\Domain\ValueObject\Status;
use PHPUnit\Framework\TestCase;

class TransitionTest extends TestCase
{
    /**
     */
    public function testObjectCreation(): void
    {
        $name = 'Any name';
        /** @var Status $source */
        $source = $this->createMock(Status::class);
        /** @var Status $destination */
        $destination = $this->createMock(Status::class);

        $status = new Transition($name, $source, $destination);
        $this->assertSame($name, $status->getName());
        $this->assertSame($source, $status->getSource());
        $this->assertSame($destination, $status->getDestination());
    }
}
