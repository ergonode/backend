<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Importer\Domain\Factory\TransformerFactory;
use PHPUnit\Framework\TestCase;

class TransformerFactoryTest extends TestCase
{
    public function testFactoryCreate(): void
    {
        /** @var TransformerId $id */
        $id = $this->createMock(TransformerId::class);
        $name = 'Any Name';
        $key = 'Any Key';

        $factory = new TransformerFactory();
        $result = $factory->create($id, $name, $key);
        $this->assertEquals($id, $result->getId());
        $this->assertEquals($name, $result->getName());
        $this->assertEquals($key, $result->getKey());
    }
}
