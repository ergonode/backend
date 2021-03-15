<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Model\Form\Type;

use Symfony\Component\Validator\Constraints as Assert;

class ColumnModel
{
    /**
     * @Assert\NotBlank()
     */
    public ?string $column;

    /**
     * @Assert\NotBlank()
     */
    public ?string $code;

    /**
     * @Assert\NotBlank()
     */
    public ?string $type;

    /**
     * @Assert\NotBlank()
     */
    public ?bool $imported;

    /**
     * ColumnModel constructor.
     */
    public function __construct()
    {
        $this->column  = null;
        $this->code = null;
        $this->imported = null;
        $this->type = null;
    }
}
