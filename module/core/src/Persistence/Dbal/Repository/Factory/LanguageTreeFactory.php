<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Repository\Factory;

use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\LanguageNode;

/**
 */
class LanguageTreeFactory
{
    /**
     * @param array $data
     *
     * @return LanguageTree
     */
    public function create(array $data): LanguageTree
    {
        $node = [];
        $root = null;
        foreach ($data as $row) {
            $node[$row['id']] = new LanguageNode(new Language($row['code']));
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
