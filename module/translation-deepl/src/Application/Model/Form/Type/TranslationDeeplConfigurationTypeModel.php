<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);


namespace Ergonode\TranslationDeepl\Application\Model\Form\Type;

use Symfony\Component\Validator\Constraints as Assert;


class TranslationDeeplConfigurationTypeModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Target Langauge is required")
     * @Assert\Length(
     *      min = 2,
     *      max = 2,
     */
    // TODO: Add Custom Validator
    public $targetLang;
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Source Language is required")
     * @Assert\Length(
     *      min = 2,
     *      max = 2,
     */
    // TODO: Add Custom Validator
    public $sourceLang;
    /**
     * @var array
     *
     */
    public $tagHandling;
    /**
     * @var array
     *
     */
    public $nonSplittingTags;
    /**
     * @var array
     *
     */
    public $ignoreTags;
    /**
     * @var string
     *
     */
    public $splitSentences;
    /**
     * @var int
     *
     */
    public $preserveFormatting;
}
