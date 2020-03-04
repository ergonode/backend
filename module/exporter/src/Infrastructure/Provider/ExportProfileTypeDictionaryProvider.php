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

/**
 */
class ExportProfileTypeDictionaryProvider
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var ExportProfileInterface ...$exportProfiles
     */
    private array $exportProfiles;

    /**
     * @param TranslatorInterface    $translator
     * @param ExportProfileInterface ...$exportProfiles
     */
    public function __construct(TranslatorInterface $translator, ExportProfileInterface ...$exportProfiles)
    {
        $this->translator = $translator;
        $this->exportProfiles = $exportProfiles;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function provide(Language $language): array
    {
        $result = [];
        foreach ($this->exportProfiles as $profile) {
            $type = $profile->getType();
            $result[$type] = $this->translator->trans($type, [], 'export_profile', $language->getCode());
        }

        asort($result);

        return $result;
    }
}
