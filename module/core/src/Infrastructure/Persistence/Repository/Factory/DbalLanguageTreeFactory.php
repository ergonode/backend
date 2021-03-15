<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Repository\Factory;

use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\Core\Domain\ValueObject\LanguageNode;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;

class DbalLanguageTreeFactory
{
    /**
     * @param array $data
     */
    public function create(array $data): LanguageTree
    {
        $node = [];
        $root = null;
        foreach ($data as $row) {
            $node[$row['id']] = new LanguageNode(new LanguageId($row['id']));
            if ($row['parent_id'] === null) {
                $root = $row['id'];
            }
        }

        foreach ($data as $row) {
            if ($row['parent_id']) {
                $node[$row['parent_id']]->addChild($node[$row['id']]);
            }
        }

        $languageTree = new LanguageTree($node[$root]);
        unset($node);

        return $languageTree;
    }
}
