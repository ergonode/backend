<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Model\Form\Type;
use Symfony\Component\Validator\Constraints as Assert;


/**
 */
class ColumnModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public ?string $column;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public ?string $code;

    /**
     * @var  string
     *
     * @Assert\NotBlank()
     */
    public ?string $type;

    /**
     * @var bool
     *
     * @Assert\NotBlank()
     */
    public ?bool $imported;
}
