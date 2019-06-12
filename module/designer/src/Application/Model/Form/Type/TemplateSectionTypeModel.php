<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form\Type;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class TemplateSectionTypeModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $row;

    /**
     * @var int
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="Max length of title is {{ limit }}")
     */
    public $title;
}
