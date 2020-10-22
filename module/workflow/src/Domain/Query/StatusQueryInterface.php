<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

interface StatusQueryInterface
{
    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getAllStatuses(Language $language): array;

    /**
     * @return array
     */
    public function getAllCodes(): array;

    /**
     * @param Language $translationLanguage
     * @param Language $workflowLanguage
     *
     * @return mixed[][]
     */
    public function getStatusCount(Language $translationLanguage, Language $workflowLanguage): array;
}
