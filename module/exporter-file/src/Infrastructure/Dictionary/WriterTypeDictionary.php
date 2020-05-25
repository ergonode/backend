<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Dictionary;

use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterTypeProvider;

/**
 */
class WriterTypeDictionary
{
    /**
     * @var WriterTypeProvider
     */
    private WriterTypeProvider $provider;

    /**
     * @var /TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param WriterTypeProvider  $provider
     * @param TranslatorInterface $translator
     */
    public function __construct(WriterTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @param Language|null $language
     *
     * @return string[]
     */
    public function dictionary(?Language $language = null): array
    {
        $code = $language ? $language->getCode() : null;
        $result = [];
        foreach ($this->provider->provide() as $type) {
            $result[$type] = $this->translator->trans($type, [], 'exporter', $code);
        }

        return $result;
    }
}
