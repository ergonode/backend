<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Tests\Domain\Entity;

use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use PHPUnit\Framework\TestCase;

/**
 */
final class ErgonodeZipSourceTest extends TestCase
{
    /**
     */
    public function testCreation(): void
    {
        $id = $this->createMock(SourceId::class);
        $name = 'Any name';
        $entity = new ErgonodeZipSource($id, $name);
        self::assertEquals($id, $entity->getId());
        self::assertEquals($name, $entity->getName());
    }
}
