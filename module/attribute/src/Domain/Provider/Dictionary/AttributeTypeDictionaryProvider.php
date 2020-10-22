<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider\Dictionary;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Attribute\Application\Provider\AttributeTypeProvider;

class AttributeTypeDictionaryProvider
{
    /**
     * @var AttributeTypeProvider
     */
    private AttributeTypeProvider $provider;
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param AttributeTypeProvider $provider
     * @param TranslatorInterface   $translator
     */
    public function __construct(AttributeTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        $result = [];
        foreach ($this->provider->provide() as $type) {
            $result[$type] = $this->translator->trans($type, [], 'attribute', $language->getCode());
        }

        return $result;
    }
}
