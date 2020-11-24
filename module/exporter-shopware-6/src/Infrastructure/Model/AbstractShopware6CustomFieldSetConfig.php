<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

abstract class AbstractShopware6CustomFieldSetConfig
{
    /**
     * @JMS\Type("bool")
     * @JMS\SerializedName("translated")
     */
    protected bool $translated;

    /**
     * @JMS\Type("array")
     * @JMS\SerializedName("label")
     */
    protected ?array $label;

    public function __construct(bool $translated = false, array $label = null)
    {
        $this->translated = $translated;
        $this->label = $label;
    }
}
