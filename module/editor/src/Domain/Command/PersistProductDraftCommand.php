<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Command;

use Ergonode\Editor\Domain\Entity\ProductDraftId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class PersistProductDraftCommand
{
    /**
     * @var ProductDraftId
     *
     * @JMS\Type("Ergonode\Editor\Domain\Entity\ProductDraftId")
     */
    private $id;

    /**
     * @param ProductDraftId $id
     */
    public function __construct(ProductDraftId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ProductDraftId
     */
    public function getId(): ProductDraftId
    {
        return $this->id;
    }
}
