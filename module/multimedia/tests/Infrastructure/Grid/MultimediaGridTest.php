<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Grid;

use Ergonode\Multimedia\Infrastructure\Grid\MultimediaGrid;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;

/**
 */
class MultimediaGridTest extends TestCase
{
    /**
     */
    public function testGridInit(): void
    {
        $configuration = $this->createMock(GridConfigurationInterface::class);
        $language = $this->createMock(Language::class);
        $provider = $this->createMock(MultimediaExtensionProvider::class);
        $provider->method('dictionary')->willReturn([]);

        $grid = new MultimediaGrid($provider);
        $grid->init($configuration, $language);

        $this->assertNotEmpty($grid->getColumns());
    }
}
