<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use PHPUnit\Framework\TestCase;

/**
 */
class TransitionTest extends TestCase
{
    /**
     */
    public function testObjectCreation(): void
    {
        /** @var StatusCode $source */
        $source = $this->createMock(StatusCode::class);
        /** @var StatusCode $destination */
        $destination = $this->createMock(StatusCode::class);
        /** @var TranslatableString $name */
        $name = $this->createMock(TranslatableString::class);
        /** @var TranslatableString $description */
        $description = $this->createMock(TranslatableString::class);

        $status = new Transition($source, $destination, $name, $description);
        $this->assertSame($source, $status->getSource());
        $this->assertSame($destination, $status->getDestination());
        $this->assertSame($name, $status->getName());
        $this->assertSame($description, $status->getDescription());
    }
}
