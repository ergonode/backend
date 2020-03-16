<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Application\Model\Form;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\TranslationDeepl\Infrastructure\Validator\Constraints as DeeplAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class TranslationDeeplFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Content to translation is required")
     * @Assert\Length(min="3", max="30000")
     */
    public ?string $content;

    /**
     * @var Language|null
     *
     * @Assert\NotBlank(message="Source language is required")
     * @Assert\NotEqualTo(propertyPath="targetLanguage", message="Source language and target language can't be equal.")
     *
     * @DeeplAssert\DeeplLanguageConstraint()
     */
    public ?Language $sourceLanguage;

    /**
     * @var Language|null
     *
     * @Assert\NotBlank(message="Target language is required")
     *
     * @DeeplAssert\DeeplLanguageConstraint()
     */
    public ?Language $targetLanguage;

    /**
     */
    public function __construct()
    {
        $this->content = null;
        $this->sourceLanguage = null;
        $this->targetLanguage = null;
    }
}
