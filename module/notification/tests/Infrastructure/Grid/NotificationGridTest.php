<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Tests\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Notification\Infrastructure\Grid\NotificationGrid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationGridTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var TranslatorInterface|MockObject $translator */
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturn('translated');
        /** @var GridConfigurationInterface|MockObject $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);
        $grid = new NotificationGrid($translator);
        $grid->init($configuration, $language);

        $this->assertNotEmpty($grid->getColumns());
    }
}
