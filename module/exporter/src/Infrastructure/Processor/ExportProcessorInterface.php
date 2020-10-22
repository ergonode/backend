<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Processor;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

interface ExportProcessorInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param ExportId        $id
     * @param AbstractChannel $channel
     */
    public function start(ExportId $id, AbstractChannel $channel): void;

    /**
     * @param ExportId        $id
     * @param AbstractChannel $channel
     * @param AbstractProduct $product
     *
     * @throws ExportException
     */
    public function process(ExportId $id, AbstractChannel $channel, AbstractProduct $product): void;

    /**
     * @param ExportId        $id
     * @param AbstractChannel $channel
     */
    public function end(ExportId $id, AbstractChannel $channel): void;
}
