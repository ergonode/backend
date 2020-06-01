<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class MultimediaRelationProvider
{
    /**
     * @var MultimediaRelationInterface[]
     */
    private array $relations;

    /**
     * @param MultimediaRelationInterface ...$relations
     */
    public function __construct(MultimediaRelationInterface ...$relations)
    {
        $this->relations = $relations;
    }

    /**
     * @param MultimediaId $id
     * @param Language     $language
     *
     * @return array
     */
    public function provide(MultimediaId $id, Language $language): array
    {
        $result = [];
        foreach ($this->relations as $relation) {
            $relations = $relation->getRelation($id, $language);
            $result[] = $relations;
        }

        return $result;
    }
}
