<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Provider;

use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class SourceTypeDictionaryProvider
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var ImportSourceInterface ...$sources
     */
    private array $sources;

    /**
     * @param TranslatorInterface         $translator
     * @param array|ImportSourceInterface ...$sources
     */
    public function __construct(TranslatorInterface $translator, ImportSourceInterface ...$sources)
    {
        $this->translator = $translator;
        $this->sources = $sources;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function provide(Language $language): array
    {
        $result = [];
        foreach ($this->sources as $source) {
            $type = $source->getType();
            $result[$type] = $this->translator->trans($type, [], 'source', $language->getCode());
        }

        asort($result);

        return $result;
    }
}
