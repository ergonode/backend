<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Processor;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

interface ExportProcessorInterface
{
    public function supported(string $type): bool;

    public function start(ExportId $id, AbstractChannel $channel): void;

    /**
     * @throws ExportException
     */
    public function process(ExportId $id, AbstractChannel $channel, AbstractProduct $product): void;

    public function end(ExportId $id, AbstractChannel $channel): void;
}
