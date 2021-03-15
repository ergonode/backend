<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Grid;

use Ergonode\Multimedia\Infrastructure\Grid\MultimediaGridBuilder;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;

class MultimediaGridTest extends TestCase
{
    public function testGridInit(): void
    {
        $configuration = $this->createMock(GridConfigurationInterface::class);
        $language = $this->createMock(Language::class);
        $provider = $this->createMock(MultimediaExtensionProvider::class);
        $provider->method('dictionary')->willReturn([]);
        $query = $this->createMock(MultimediaQueryInterface::class);
        $query->method('getTypes')->willReturn([]);

        $builder = new MultimediaGridBuilder($provider, $query);
        $grid = $builder->build($configuration, $language);

        self::assertNotEmpty($grid->getColumns());
    }
}
