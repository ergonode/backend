<?php
/*
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldSetConfig;
use PHPUnit\Framework\TestCase;

class AbstractShopware6CustomFieldSetConfigTest extends TestCase
{
    private bool $translated;

    private array $label;

    protected function setUp(): void
    {
        $this->translated = true;
        $this->label = [];
    }

    public function testCreateModel(): void
    {
        $model = $this->getClass();

        self::assertEquals($this->translated, $model->isTranslated());
        self::assertEquals($this->label, $model->getLabel());
    }

    private function getClass(): AbstractShopware6CustomFieldSetConfig
    {
        return new class(
            $this->translated,
            $this->label,
        ) extends AbstractShopware6CustomFieldSetConfig {
        };
    }
}
