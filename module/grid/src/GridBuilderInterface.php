<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Ergonode\Core\Domain\ValueObject\Language;

interface GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface;
}
