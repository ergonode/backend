<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Provider\Dictionary;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Attribute\Application\Provider\AttributeTypeProvider;

class AttributeTypeDictionaryProvider
{
    private AttributeTypeProvider $provider;
    private TranslatorInterface $translator;

    public function __construct(AttributeTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
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
