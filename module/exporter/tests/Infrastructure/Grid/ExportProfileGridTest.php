<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Infrastructure\Grid;

use Ergonode\Exporter\Infrastructure\Grid\ExportProfileGrid;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Infrastructure\Provider\ExportProfileTypeDictionaryProvider;
use PHPUnit\Framework\MockObject\MockObject;

/**
 */
class ExportProfileGridTest extends TestCase
{
    /**
     */
    public function testGridInit(): void
    {
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);
        /** @var ExportProfileTypeDictionaryProvider|MockObject $provider */
        $provider = $this->createMock(ExportProfileTypeDictionaryProvider::class);
        $provider->expects($this->once())->method('provide')->willReturn([]);
        $grid = new ExportProfileGrid($provider);
        $grid->init($configuration, $language);
    }
}
