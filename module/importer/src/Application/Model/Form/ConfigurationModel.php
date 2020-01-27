<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Model\Form;

use Ergonode\Importer\Application\Model\Form\Type\ColumnModel;
use Symfony\Component\Validator\Constraints as Assert;


/**
 */
class ConfigurationModel
{
    /**
     * @var ColumnModel[]
     *
     * @Assert\Valid()
     */
    public array $columns;

    /**
     */
    public function __construct()
    {
        $this->columns = [];
    }
}
