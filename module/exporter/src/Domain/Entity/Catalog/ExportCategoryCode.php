<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity\Catalog;

use JMS\Serializer\Annotation as JMS;

/**
 */
class ExportCategoryCode
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
