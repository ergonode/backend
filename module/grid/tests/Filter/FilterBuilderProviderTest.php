<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests\Filter;

use Ergonode\Grid\Filter\Builder\FilterBuilderInterface;
use Ergonode\Grid\Filter\FilterBuilderProvider;
use PHPUnit\Framework\TestCase;
use Ergonode\Grid\FilterInterface;

class FilterBuilderProviderTest extends TestCase
{
    protected FilterBuilderInterface $filterBuilder;

    protected FilterInterface $filter;

    protected function setUp(): void
    {
        $this->filterBuilder = $this->createMock(FilterBuilderInterface::class);
        $this->filter = $this->createMock(FilterInterface::class);
    }

    public function testNotExistingFilter(): void
    {
        $this->filterBuilder->method('supports')->willReturn(false);
        $builderCollection = [$this->filterBuilder];
        $this->expectException(\RuntimeException::class);
        $provider = new FilterBuilderProvider($builderCollection);
        $provider->provide($this->filter);
    }

    public function testExistingFilter(): void
    {
        $this->filterBuilder->method('supports')->willReturn(true);
        $builderCollection = [$this->filterBuilder];
        $provider = new FilterBuilderProvider($builderCollection);
        $builder = $provider->provide($this->filter);
        self::assertSame(get_class($builder), get_class($this->filterBuilder));
    }
}
