<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\GridConfigurationInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractGridTestCase extends TestCase
{
    protected function getDataSet(\Traversable $collection = null): DataSetInterface
    {
        if (null === $collection) {
            $collection = new ArrayCollection();
        }

        /** @var DataSetInterface|MockObject $dataSet */
        $dataSet = $this->createMock(DataSetInterface::class);
        $dataSet->expects($this->atLeast(1))->method('getItems')->willReturn($collection);

        return $dataSet;
    }

    protected function getPagination(): GridConfigurationInterface
    {
        return $this->createMock(GridConfigurationInterface::class);
    }
}
