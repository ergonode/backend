<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

/**
 */
class EndShopware6ExportProcess
{
    /**
     * @param ExportId        $id
     * @param AbstractChannel $channel
     */
    public function process(ExportId $id, AbstractChannel $channel): void
    {
    }
}
