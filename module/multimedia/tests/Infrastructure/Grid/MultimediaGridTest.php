<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Infrastructure\Grid\MultimediaGridBuilder;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MultimediaGridTest extends TestCase
{
    /**
     * @var GridConfigurationInterface|MockObject
     */
    private $configuration;

    /**
     * @var Language|MockObject
     */
    private $language;

    /**
     * @var MultimediaExtensionProvider|MockObject
     */
    private $provider;

    /**
     * @var MultimediaQueryInterface|MockObject
     */
    private $query;

    private MultimediaGridBuilder $builder;

    protected function setUp(): void
    {
        $this->configuration = $this->createMock(GridConfigurationInterface::class);
        $this->language = $this->createMock(Language::class);
        $this->provider = $this->createMock(MultimediaExtensionProvider::class);
        $this->query = $this->createMock(MultimediaQueryInterface::class);
        $this->builder = new MultimediaGridBuilder($this->provider, $this->query);
    }

    public function testGridInit(): void
    {
        $this->provider->method('dictionary')->willReturn([]);
        $this->query->method('getTypes')->willReturn([]);

        $builder = $this->builder;
        $grid = $builder->build($this->configuration, $this->language);

        self::assertNotEmpty($grid->getColumns());
    }

    public function testExtension(): void
    {
        $this->provider->method('dictionary')->willReturn(['c', 'b', 'a']);
        $this->query->method('getTypes')->willReturn([]);

        $result = ['a', 'b', 'c'];
        $i = 0;

        $builder = $this->builder;
        $grid = $builder->build($this->configuration, $this->language);
        $options = $grid->getColumns()['extension']->getFilter()->render()['options'];
        foreach ($options as $option) {
            self::assertEquals($result[$i], $option['label']);
            $i++;
        }
    }
}
