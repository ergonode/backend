<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Provider;

use Ergonode\Multimedia\Infrastructure\Provider\MultimediaRelationProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaRelationInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\Language;

class MultimediaRelationProviderTest extends TestCase
{
    public function testProviderWithoutInterfaces(): void
    {

        $id = $this->createMock(MultimediaId::class);
        $language = $this->createMock(Language::class);

        $provider = new MultimediaRelationProvider(...[]);
        $result = $provider->provide($id, $language);
        $this->assertEmpty($result);
    }

    public function testProviderWithInterfaces(): void
    {
        $interface = $this->createMock(MultimediaRelationInterface::class);
        $interface->expects($this->once())->method('getRelation')->willReturn(['array']);
        $id = $this->createMock(MultimediaId::class);
        $language = $this->createMock(Language::class);

        $provider = new MultimediaRelationProvider(...[$interface]);
        $result = $provider->provide($id, $language);
        $this->assertSame([['array']], $result);
    }
}
