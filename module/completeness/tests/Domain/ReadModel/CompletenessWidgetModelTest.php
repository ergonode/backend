<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Tests\Domain\ReadModel;

use Ergonode\Completeness\Domain\ReadModel\CompletenessWidgetModel;
use PHPUnit\Framework\TestCase;

class CompletenessWidgetModelTest extends TestCase
{
    public function testProperCreation(): void
    {
        $code = 'Any code';
        $label = 'Any Name';
        $value = 10;

        $model = new CompletenessWidgetModel($code, $label, $value);

        $this::assertEquals($code, $model->getCode());
        $this::assertEquals($label, $model->getLabel());
        $this::assertEquals($value, $model->getValue());
    }
}
