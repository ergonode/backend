<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity\Catalog;

use Ergonode\Category\Domain\ValueObject\Node;
use Ramsey\Uuid\Uuid;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ExportTree
{
    /**
     * @var Uuid
     *
     * @JMS\Type("uuid")
     */
    private Uuid $id;

    /**
     * @var Node[]
     *
     * @JMS\Type("array<Ergonode\Category\Domain\ValueObject\Node>")
     */
    private array $categories;

    /**
     * @param Uuid         $id
     * @param array|Node[] $categories
     */
    public function __construct(Uuid $id, $categories)
    {
        $this->id = $id;
        $this->categories = $categories;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return Node[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}
