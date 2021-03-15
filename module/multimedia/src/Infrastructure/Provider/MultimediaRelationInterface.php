<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\Language;

interface MultimediaRelationInterface
{
    /**
     * @return array
     */
    public function getRelation(MultimediaId $multimediaId, Language $language): array;
}
