<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Model\Form;

use Ergonode\Importer\Application\Model\Form\Type\ColumnModel;

class ConfigurationModel
{
    /**
     * @var ColumnModel[]
     */
    public array $columns;

    public function __construct()
    {
        $this->columns = [];
    }
}
