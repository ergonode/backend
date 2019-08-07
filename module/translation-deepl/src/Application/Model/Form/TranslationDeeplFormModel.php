<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Application\Model\Form;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class TranslationDeeplFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Content to translation is required")
     * @Assert\Length(min="3", max="30000")
     */
    public $content;

    /**
     * @var Language
     *
     * @Assert\NotBlank(message="Source language is required")
     */
    public $sourceLanguage;

    /**
     * @var Language
     *
     * @Assert\NotBlank(message="Target language is required")
     */
    public $targetLanguage;
}
