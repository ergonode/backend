<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Dictionary;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Importer\Domain\ValueObject\ImportStatus;

class ImportStatusDictionary
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        $result = [];
        foreach (ImportStatus::AVAILABLE as $status) {
            $result[$status] = $this->translator->trans($status, [], 'import', $language->getCode());
        }

        return $result;
    }
}
