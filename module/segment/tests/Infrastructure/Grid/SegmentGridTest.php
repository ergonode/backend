<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Tests\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Segment\Infrastructure\Grid\SegmentGridBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class SegmentGridTest extends TestCase
{
    public function testGridInit(): void
    {
        /** @var TranslatorInterface|MockObject $translator */
        $translator = $this->createMock(TranslatorInterface::class);
        $translator->method('trans')->willReturn('translated');
        /** @var GridConfigurationInterface $configuration */
        $configuration = $this->createMock(GridConfigurationInterface::class);
        /** @var Language $language */
        $language = $this->createMock(Language::class);

        $builder = new SegmentGridBuilder($translator);
        $grid = $builder->build($configuration, $language);

        $this->assertNotEmpty($grid->getColumns());
    }
}
