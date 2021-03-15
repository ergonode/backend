<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

class EndShopware6ExportProcess
{
    public function process(Export $export, AbstractChannel $channel): void
    {
    }
}
