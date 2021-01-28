<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Core\Application\Validator as CoreAssert;

class BatchActionValueTranslationModel
{
    /**
     * @Assert\NotBlank()
     * @CoreAssert\LanguageCode()
     * @CoreAssert\LanguageCodeActive()
     */
    public ?string $language = null;

    /**
     * @var mixed
     */
    public $value = null;
}
