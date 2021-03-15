<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Provider;

use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class SourceTypeDictionaryProvider
{
    private SourceTypeProvider $provider;

    private TranslatorInterface $translator;

    public function __construct(SourceTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function provide(Language $language): array
    {
        $result = [];
        foreach ($this->provider->provide() as $type) {
            $result[$type] = $this->translator->trans($type, [], 'source', $language->getCode());
        }

        asort($result);

        return $result;
    }
}
