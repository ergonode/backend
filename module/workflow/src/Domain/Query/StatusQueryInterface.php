<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;

interface StatusQueryInterface
{
    /**
     * @return array
     */
    public function getDictionary(Language $language): array;

    /**
     * @return array
     */
    public function getAllStatuses(Language $language): array;

    /**
     * @return array
     */
    public function getAllCodes(): array;

    /**
     * @return mixed[][]
     */
    public function getStatusCount(Language $translationLanguage, Language $workflowLanguage): array;

    /**
     * @return array
     */
    public function getAllStatusIds(): array;
}
