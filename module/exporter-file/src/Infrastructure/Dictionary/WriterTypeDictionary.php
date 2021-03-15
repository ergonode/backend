<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Dictionary;

use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterTypeProvider;

class WriterTypeDictionary
{
    private WriterTypeProvider $provider;

    private TranslatorInterface $translator;

    public function __construct(WriterTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @return string[]
     */
    public function dictionary(?Language $language = null): array
    {
        $code = $language ? $language->getCode() : null;
        $result = [];
        foreach ($this->provider->provide() as $type) {
            $result[$type] = $this->translator->trans($type, [], 'channel', $code);
        }

        return $result;
    }
}
