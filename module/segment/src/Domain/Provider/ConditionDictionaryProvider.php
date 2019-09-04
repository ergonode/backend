<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Segment\Domain\Condition\AttributeExistsCondition;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ConditionDictionaryProvider
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        return [
            AttributeExistsCondition::TYPE => $this->translator->trans(AttributeExistsCondition::TYPE, [], 'segment', $language->getCode()),
        ];
    }
}
