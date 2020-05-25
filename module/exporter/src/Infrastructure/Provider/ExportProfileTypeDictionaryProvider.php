<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Exporter\Application\Provider\ExportProfileTypeProvider;

/**
 */
class ExportProfileTypeDictionaryProvider
{
    /**
     * @var ExportProfileTypeProvider
     */
    private ExportProfileTypeProvider $provider;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param ExportProfileTypeProvider $provider
     * @param TranslatorInterface       $translator
     */
    public function __construct(ExportProfileTypeProvider $provider, TranslatorInterface $translator)
    {
        $this->provider = $provider;
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function provide(Language $language): array
    {
        $result = [];
        foreach ($this->provider->provide() as $type) {
            $result[$type] = $this->translator->trans($type, [], 'export_profile', $language->getCode());
        }

        asort($result);

        return $result;
    }
}
