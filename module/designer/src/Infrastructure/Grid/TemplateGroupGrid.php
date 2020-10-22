<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

class TemplateGroupGrid extends AbstractGrid
{
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $this->addColumn('id', new TextColumn('id', 'Id'));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('custom', new BoolColumn('custom', 'Icon'));
    }
}
